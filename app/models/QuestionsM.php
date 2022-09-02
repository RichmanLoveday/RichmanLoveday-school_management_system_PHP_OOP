<?php
/*
** Question Model
*
*/
declare(strict_types=1);

use app\core\Model;

class QuestionsM extends Model {

    protected $table = 'test_questions';

    protected $allowedColumns = [
        'question',
        'test_id',
        'question',
        'question_type',
        'correct_answer',
        'choices', 
        'date',
        'image',
        'comment',
    ];

    protected $beforeInsert = [
        'user_id', 
    ];

    protected $afterSelect = [
        'get_user',  
    ];

    public function validate($data): bool {
        //show($data); die;
        // print_r($_SESSION['USER']);
        // die();
        $this->errors = [];

        // Check for queation name
        if(empty($data['question'])) {
            $this->errors['question'] = 'Pls add a valid question';
        } 

        // checking for multiple choice answers
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'I', 'J'];
        $num = 0;
        foreach($data as $key => $value) {
            if(strstr($key, 'choice')) {
                if(empty($value)) {
                    $this->errors['choice'.$num] = 'Pls add a valid answer in choice'.' '.$letters[$num];
                }
                $num++;
            }
        }
   
        if(isset($data['answer'])) {
            if(empty($data['answer'])) {
                $this->errors['answer'] = 'Pls add a valid answer';
            }
        }

        // Checking if errors are empty
        if(empty($this->errors)) {
            return true;
        }

        return false;
    }


    public function user_id(array $data) {
        
        if(isset($_SESSION['USER']->user_id)) {
            $data['user_id'] = $_SESSION['USER']->user_id;
        }
        return $data;
        
    }


    // Data is an array of objects in rows returned
    public function get_user(array $data) {
        //show($data); 

        $user = new User();
        if(isset($data[0]->user_id)) {
            foreach($data as $key => $row) {
                $result = $user->where('user_id', $row->user_id);
                //show($result); die;
                $data[$key]->user = is_array($result) ? $result[0] : false;
            }
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
    public function fetchClass($user_id,  $table = 'class_students', $status = 0): array {
        $this->query("SELECT * FROM $table WHERE user_id = :id AND status = :status ORDER BY id DESC");
        $this->bind(':id', $user_id);
        $this->bind(':status', $status);
        $result = $this->resultSet();
        return $result;
    }


    // find class by searching, strickly for admin and super admin, creators of classes
    public function find_all_class(string $school_id, $search): array {
        $this->query("SELECT * FROM classes WHERE school_id = :user_id AND (class like :search) ORDER BY id DESC");
        $this->bind(':user_id', $school_id);
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
   


    // Fectch student by class_id
    public function fetch_tests(string $id, $limit, $offset) {         
        $this->query("SELECT * FROM tests WHERE class_id = :id ORDER BY id DESC limit $limit offset $offset");
        $this->bind(':id', $id);
        $result = $this->resultSet();

        if(is_array($result)) {
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $result = $this->$func($result);
                }
            }
        }

        // show($result);
        // die;

        if(is_array($result) && count($result) > 0) {
            return $result;
            
        }
        return false;
    }

    public function get_all_quest($test_id): array {
        $this->query("SELECT id FROM $this->table WHERE test_id = :test_id");
        $this->bind(':test_id', $test_id);
        $this->executeBind();

        return $this->resultSet();

    }

}