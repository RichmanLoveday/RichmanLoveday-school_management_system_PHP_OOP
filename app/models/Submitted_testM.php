<?php
/*
** Submitted_test Model
*
*/
declare(strict_types=1);
use app\core\Model;

class Submitted_testM extends Model {

    protected $table = 'submited_test';

    protected $allowedColumns = [];

    protected $beforeInsert = [];

    protected $afterSelect = [
        'get_user',  
    ];

    
    // Data is an array of objects in rows returned
    public function get_user(array $data) {
        $user = new User();
        foreach($data as $key => $row) {
            $result = $user->where('user_id', $row->user_id);
            //show($result); die;
            $data[$key]->user = is_array($result) ? $result[0] : false;
        }
        return $data;
    }


    public function get_unsubmitted_test() {
        if(Auth::getRank() == 'student') {
            $this->query("SELECT * FROM tests WHERE class_id IN (SELECT class_id FROM class_students WHERE user_id = :user_id ) AND test_id NOT IN (SELECT test_id FROM `submited_test` WHERE user_id = :user_id AND submitted = 1) AND status = 0 ");
            $this->bind(':user_id', Auth::getUser_id());
            $this->executeBind();
            $data = count($this->resultSet());
    
            if($data > 0) {
                return $data;
            }
        }
        return 0;
    }

    public function get_unsubmitted_rows():array {
        $this->query("SELECT test_id FROM tests WHERE class_id IN (SELECT class_id FROM class_students WHERE user_id = :user_id ) AND test_id NOT IN (SELECT test_id FROM `submited_test` WHERE user_id = :user_id AND submitted = 1) AND status = 0 ");
        $this->bind(':user_id', Auth::getUser_id());
        $this->executeBind();
        $data = $this->resultSet();

        if($data > 0) {
            $data = array_column($data, 'test_id');
            return $data;
        }

        return [];
    }
}