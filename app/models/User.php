<?php
/*
** User Model
*
*/

declare(strict_types=1);
use app\core\Model;

class User extends Model {

    //protected $table = 'users';

    protected $allowedColumns = [
        'firstName', 
        'lastName', 
        'email',
        'password',
        'gender',
        'rank',
        'date',
        'image',
        'school_id',
    ];

    protected $beforeInsert = [
        'school_id', 
        'user_id', 
        'password_hash',
    ];

    protected $beforeUpdate = [
        'password_hash',
    ];

    protected $afterSelect = [
        'get_school_name',
    ];

    
    public function validate($data, $user_id = ''): bool {
        //show($data); die;
        $this->errors = [];

        // Validate first Name
        if(empty($data['firstName'])) {
            $this->errors['firstname'] = 'Pls fill in this field';
        } elseif(!preg_match('/^[a-zA-Z]+$/', $data['firstName'])) {
            $this->errors['firstname'] = 'Only letters allowed in First Name';
        } else {
            echo '';
        }


        // Validate last Name
        if(empty($data['lastName'])) {
            $this->errors['lastname'] = 'Pls fill in this field';
        } elseif(!preg_match('/^[a-zA-Z]+$/', $data['lastName'])) {
            $this->errors['lastname'] = 'Only letters allowed in Last Name';
        } else {
            echo '';
        }


        // Validate Password
        if(isset($data['password'])) {

            if(empty($data['password'])) {
                $this->errors['password'] = 'Pls fill in this field';
    
            }elseif(strlen($data['password']) < 6) {
                $this->errors['password'] = 'Password must be atleast 6 characters long     ';
            } elseif($data['password'] != $data['confirm_password']) {
                $this->errors['password'] = 'The password do not match';
            } else {
                echo '';
            }

        }
        

        // Validate Email 
        if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Pls fill in this field';

        } elseif(!empty($data['email'])) {

            //$this->where('email', $data['email']);

            $this->query('SELECT * FROM users WHERE user_id != :user_id AND email = :email');
            $this->bind(':user_id', $user_id);
            $this->bind(':email', $data['email']);
            $this->resultSet();

            if($this->rowCount() > 0) {
                $this->errors['email'] = 'Email already exists';
            }
        }


        // Validate Gender
        $gender = ['Male', 'Female'];

        if(empty($data['gender']) && !in_array($data['gender'], $gender)) {
            $this->errors['gender'] = 'Gender is not valid';
        } 

        // Validate rank
        $rank = ['student', 'reception', 'lecturer', 'admin', 'super_admin'];
        if(empty($data['rank']) && !in_array($data['rank'], $rank)) {
            $this->errors['rank'] = 'Rank is not valid';
        }

        // Checking if errors are empty
        if(empty($this->errors)) {
            return true;
        }

        //show($this->errors); die;
        return false;
    }

    public function school_id(array $data) {
        if(isset($_SESSION['USER']->school_id)) {
            $data['school_id'] = $_SESSION['USER']->school_id;
        }
        return $data;
    
    }

    public function user_id(array $data) {
        $data['user_id'] = strtolower($data['firstName'] . '.' . $data['lastName']);
        
        while($this->where('user_id', $data['user_id'])) {
            $data['user_id'] .= rand(10, 1000);
        }

        // $data['user_id'] = random_string(60);
        return $data;
    }


    public function password_hash(array $data) {

        if(isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }   
        return $data;
    }

    
    public function get_school_name(array $data):array {
        
        foreach($data as $keys => $value) {
            if(isset($data[$keys]->school_id)) {
                $this->query("SELECT * FROM schools WHERE school_id = :skl_id ");
                $this->bind(':skl_id', $data[$keys]->school_id);
                $row = $this->single();
    
                if($row) {
                    $data[$keys]->school_name = $row->school;

                }
            }
        }
        //show($data); die;
        return $data;

        
        
    }

    public function get_staffs(string $school_id, $limit, $offset): array {
        $this->query("SELECT * FROM users WHERE school_id  = :school_id AND rank IN ('reception', 'admin', 'lecturer') ORDER BY id DESC limit $limit offset $offset");
        $this->bind(':school_id', $school_id);
        return $this->resultSet();
    }

    public function get_students(string $school_id, $limit, $offset): array {

         // setting paginations of offsets
        // $limit = 1;
        // $offset = ($page_num - 1) * $limit;


        $this->query("SELECT * FROM users WHERE school_id  = :school_id AND rank IN ('student') ORDER BY id DESC limit $limit offset $offset");
        $this->bind(':school_id', $school_id);
        return $this->resultSet();
    }
    

    // find student by searching
    public function find_student(string $school_id, $search, $limit, $offset): array {
    
        $this->query("SELECT * FROM users WHERE school_id  = :school_id AND rank IN ('student') AND (firstname like :search || lastname like :search) ORDER BY id DESC limit $limit offset $offset");
        $this->bind(':school_id', $school_id);
        $this->bind(':search', $search);
        $this->bind(':search', $search);
        return $this->resultSet();
    }


    // find staff by searching 
    public function find_staff(string $school_id, $search, $limit, $offset): array {
        $this->query("SELECT * FROM users WHERE school_id  = :school_id AND rank IN ('student', 'lecturer', 'reception') AND (firstname like :search || lastname like :search) ORDER BY id DESC limit $limit offset $offset");
        $this->bind(':school_id', $school_id);
        $this->bind(':search', $search);
        $this->bind(':search', $search);
        return $this->resultSet();
    }


    // search for lecturer from the users table
    public function search_lecturer(string $data, $school_id, $limit, $offset) {
        
        $this->query("SELECT * FROM users WHERE rank IN ('lecturer') AND school_id = :school_id AND (firstname like :fname || lastname like :lname) ORDER BY id DESC limit $limit offset $offset"); 
        $this->bind(':fname', $data);
        $this->bind('lname', $data);
        $this->bind(':school_id', $school_id);
        return $this->resultSet();

    }

    public function search_student(string $data, $school_id, $limit, $offset) {
        
        $this->query("SELECT * FROM users WHERE rank IN ('student') AND school_id = :school_id AND (firstname like :fname || lastname like :lname) ORDER BY id DESC limit $limit offset $offset"); 
        $this->bind(':fname', $data);
        $this->bind('lname', $data);
        $this->bind(':school_id', $school_id);
        return $this->resultSet();

    }
    
} 
	