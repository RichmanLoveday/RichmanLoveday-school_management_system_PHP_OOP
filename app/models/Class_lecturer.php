<?php
/*
** Classes Model
*
*/
declare(strict_types=1);

use app\core\Model;
use LDAP\Result;

class Class_lecturer extends Model {

    //protected $table = 'classes';

    protected $allowedColumns = [
        'class_id',
        'user_id',
        'status',
        'date',
    ];

    protected $beforeInsert = [
        'school_id', 
    ];

    protected $afterSelect = [
        'get_user',  
    ];

    public function school_id(array $data) {

        if(isset($_SESSION['USER']->school_id)) {
            $data['school_id'] = $_SESSION['USER']->school_id;
        }
        return $data;
    }


    // Data is an array of objects in rows returned
    public function get_user(array $data) {

        $user = new User();

        foreach($data as $key => $row) {
            $result = $user->where('user_id', $row->user_id);
            $data[$key]->user = is_array($result) ?  $result[0] : false;
        }
        
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // die();
       
        return $data;
    }

    public function lecturer_exist(array $data) {
        $user_id = $data['user_id'];
        $class_id = $data['class_id'];

        //show($class_id); die;
        
        $this->query("SELECT * FROM class_lecturers WHERE user_id = :user_id AND class_id = :class_id limit 1");
        $this->bind(':user_id', $user_id);
        $this->bind('class_id', $class_id);
       // $this->bind(':status', $status);

        if($this->resultSet()) {
            return $this->resultSet();
        } else {
            return false;
        }
    }

    
    public function fetch_lecturers(string $id, $limit, $offset, int $status = 0) {
        $this->query("SELECT * FROM class_lecturers WHERE class_id = :id AND status = :status ORDER BY id DESC limit $limit offset $offset");
        $this->bind(':id', $id);
        $this->bind(':status', $status);
        $result = $this->resultSet();
        //show($result); die;
        if(is_array($result)) {
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $result = $this->$func($result);
                }
            }
        }

        //show($result);
        // die;

        if(is_array($result) && count($result) > 0) {
            return $result;
            
        }
        return false;
        
    }


}