<?php
/*
** Classes Model
*
*/
declare(strict_types=1);

use app\core\Model;


class ClassM extends Model {

    protected $table = 'classes';

    protected $allowedColumns = [
        'date',
        'class', 
    ];

    protected $beforeInsert = [
        'school_id',
        'class_id', 
        'user_id', 
    ];

    protected $afterSelect = [
        'get_user',  
    ];

    public function validate($data): bool {
        
        // print_r($_SESSION['USER']);
        // die();
        $this->errors = [];

        // Validate class
        if(empty($data['class'])) {
            $this->errors['class'] = 'Pls fill in this field';
        } elseif(!preg_match('/^[a-z A-Z0-9]+$/', $data['class'])) {
            $this->errors['class'] = 'Only letters allowed';
        } else {
            echo '';
        }


        // Checking if errors are empty
        if(empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function school_id(array $data) {

        if(isset($_SESSION['USER']->school_id)) {
            $data['school_id'] = $_SESSION['USER']->school_id;
        }
        return $data;
    }

    public function class_id(array $data) {
        
        $data['class_id'] = random_string(60);

        
        return $data;
    
    }

    public function user_id(array $data) {
        
        if(isset($_SESSION['USER']->user_id)) {
            $data['user_id'] = $_SESSION['USER']->user_id;
        }
        return $data;
        
    }


    // Data is an array of objects in rows returned
    public function get_user(array $data) {
        //show($data); die;
        $user = new User();

        foreach($data as $key => $row) {
            $result = $user->where('user_id', $row->user_id);
            //show($result); die;
            $data[$key]->user = is_array($result) ? $result[0] : false;
        }
        
        //show($data);
       
        return $data;
    }


    public function getClass($id) {
        $this->query("SELECT * FROM classes WHERE id = :id ");
        $this->bind(':id', $id);

        return $this->single();

    }

    
    // Get student/lecturer by user_id ID
    public function fetchClass(string $user_id, int $year, string $table = 'class_students', int $status = 0): array {
        $this->query("SELECT * FROM $table WHERE user_id = :id AND status = :status AND year(date) = :year ORDER BY id DESC");
        $this->bind(':id', $user_id);
        $this->bind(':status', $status);
        $this->bind(':year', $year);
        $result = $this->resultSet();
        return $result;
    }


    // find class by searching, strickly for admin and super admin, creators of classes
    public function find_all_class(string $school_id, $search, $year): array {
        $this->query("SELECT * FROM classes WHERE school_id = :user_id AND (class like :search) AND year(date) = :year ORDER BY id DESC");
        $this->bind(':user_id', $school_id);
        $this->bind(':search', $search);
        $this->bind(':year', $year);
        $result = $this->resultSet();


        if(is_array($result)) {
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $result = $this->$func($result);
                }
            }
        }

        return $result;
        //show($result); die;
    }


    // find class by searching, strickly for admin and super admin, creators of classes
    public function find_all_classes(string $school_id, $year): array {
        $this->query("SELECT * FROM classes WHERE school_id = :user_id AND year(date) = :year ORDER BY id DESC");
        $this->bind(':user_id', $school_id);
        $this->bind(':year', $year);
        $result = $this->resultSet();


        if(is_array($result)) {
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $result = $this->$func($result);
                }
            }
        }

        return $result;
        //show($result); die;
    }


    // finding class and getting the creator of the class 
    public function findClass(string $class_id, $search): array {
        $this->query("SELECT * FROM classes WHERE class_id = :class_id AND (class like :search) ORDER BY id DESC");
        $this->bind(':class_id', $class_id);
        $this->bind(':search', $search);
        $result = $this->resultSet();


        if(is_array($result)) {
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $result = $this->$func($result);
                }
            }
        }

        return $result;
        //show($result); die;
    }
   



}