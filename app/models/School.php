<?php
/*
** User Model
*
*/

declare(strict_types=1);
use app\core\Model;

class School extends Model {

    // protected $table = 'users';

    protected $allowedColumns = [
        'date',
        'school', 
    ];

    protected $beforeInsert = [
        'school_id', 
        'user_id', 
    ];

    protected $afterSelect = [
        'get_user',  
    ];

    public function validate($data): bool {
        $this->errors = [];

        // Validate school
        if(empty($data['school'])) {
            $this->errors['school'] = 'Pls fill in this field';
        } elseif(!preg_match('/^[a-zA-Z ]+$/', $data['school'])) {
            $this->errors['school'] = 'Only letters allowed';
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
        
        $data['school_id'] = random_string(60);
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

        $user = new User();

        foreach($data as $key => $row) {
            $result = $user->where('user_id', $row->user_id);
            $data[$key]->user = is_array($result) ? $result[0] : false;
        }
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // die();
        return $data;
    }


    public function getSchool($id) {
        
        $this->query("SELECT * FROM schools WHERE id = :id ");
        $this->bind(':id', $id);

        return $this->single();


    }

    public function get_school_name($data): object {
            //show($data); die;
            if(isset($data->school_id)) {
                $this->query("SELECT * FROM schools WHERE school_id = :skl_id ");
                $this->bind(':skl_id', $data->school_id);
                $row = $this->single();
    
                if($row) {
                    $data->school_name = $row->school;
                }
            }

        return $data;  
        
    }



   



}