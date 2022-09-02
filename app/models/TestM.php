<?php
/*
** Tests Model
*
*/
declare(strict_types=1);

use app\core\Model;


class TestM extends Model {

    protected $table = 'tests';

    protected $allowedColumns = [
        'test',
        'date',
        'class_id', 
        'description',
        'status',
        'editable',
        'description',
    ];

    protected $beforeInsert = [
        'school_id',
        'user_id', 
        'test_id',
    ];

    protected $afterSelect = [
        'get_user',  
        'get_class',
    ];

    public function validate($data): bool {
        
        // print_r($_SESSION['USER']);
        // die();
        $this->errors = [];

        // Validate test
        if(empty($data['test'])) {
            $this->errors['test'] = 'Pls fill in this field';
        } elseif(!preg_match('/^[a-z A-Z0-9]+$/', $data['test'])) {
            $this->errors['test'] = 'Only letters allowed';
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

    public function test_id(array $data) {
        $data['test_id'] = random_string(60);
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
            //show($result); die;
            $data[$key]->user = is_array($result) ? $result[0] : false;
        }
       
        return $data;
    }


    // Data is an array of objects in rows returned
    public function get_class(array $data) {
        //show($data); die;
        $class = new ClassM();

        foreach($data as $key => $row) {
            if(!empty($row->class_id)) {
                $result = $class->where('class_id', $row->class_id);
                //show($result); die;
                $data[$key]->class = is_array($result) ? $result[0] : false;
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
    public function fetchClass($user_id,  $table = 'class_students', $year, $status = 0): array {
        $this->query("SELECT * FROM $table WHERE user_id = :id AND status = :status AND year(date) = :year ORDER BY id DESC");
        $this->bind(':id', $user_id);
        $this->bind(':year', $year);
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
            return $result;
        }

        return [];
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


    
    // finding test by school id
    public function find_test_skl(string $id, $search, $year): array {
        $this->query("SELECT * FROM $this->table WHERE school_id = :id AND (test like :search) AND year(date) = :year ORDER BY id DESC");
        $this->bind(':id', $id);
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


    // finding test by school id
    public function find_test_skl2(string $id, $year): array {
        $this->query("SELECT * FROM $this->table WHERE school_id = :id AND year(date) = :year ORDER BY id DESC");
        $this->bind(':id', $id);
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


    // Find test by searching
    public function find_test_cl($user_id, $year, $table = NULL, $test_status = NULL, $find = NULL, $class_status = 0) {
       // echo $find; echo $table; die;
        $table = is_null($table) ? 'class_students' : $table;
        $test_status = is_null($test_status) ? 'status = 0 &&' : 'status = 1 &&';
        $find = is_null($find) ? '' : $find;
        $this->query("SELECT * FROM $this->table WHERE $test_status class_id IN (SELECT class_id FROM $table WHERE user_id = :id AND $class_status = :status) AND test like :find AND year(date) = :year ORDER BY id DESC");
        $this->bind(':id', $user_id);
        $this->bind(':find', $find);
        $this->bind(':year', $year);
        $this->bind(':status', $class_status);
        $result = $this->resultSet();
        
        if(is_array($result)) {
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $result = $this->$func($result);
                }
            }
        }
       //show($result); die;
        return $result;
        //show($result); die;
    }

    
   // finding class and getting the creator of the class 
   public function find_test(string $user_id, $year, string $table = NULL, string $test_status = NULL, int $class_status = 0): array {

    $table = is_null($table) ? 'class_students' : $table;
    $test_status = is_null($test_status) ? 'status = 0 &&' : 'status = 1 &&';

    $this->query("SELECT * FROM $this->table WHERE $test_status class_id IN (SELECT class_id FROM $table WHERE user_id = :id AND $class_status = :status) AND year(date) = :year ORDER BY id DESC");
    $this->bind(':id', $user_id);
    $this->bind(':status', $class_status);
    $this->bind(':year', $year);
    $result = $this->resultSet();
    
    if(is_array($result)) {
        if(property_exists($this, 'afterSelect')) {
            foreach($this->afterSelect as $func) {
                $result = $this->$func($result);
            }
        }
    }
  //  show($result); die;
    return $result;
    //show($result); die;
}


    // Fectch student by class_id
    public function fetch_test(string $id, $limit, $offset) {         
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
    


     // Fectch student by class_id
     public function fetch_stu_tests(string $id) {         
        $this->query("SELECT * FROM tests WHERE class_id in ($id) ORDER BY id DESC");
        $this->bind(':id', $id);
        $result = $this->resultSet();
        
        if(is_array($result)) {
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $result = $this->$func($result);
                }
            }
        }

        if(is_array($result) && count($result) > 0) {
            return $result;
        }
        return false;
    }


    public function single_update($id, string $column, int $value) {
       // echo $value; die;
        $this->query("UPDATE tests SET $column = :edit WHERE test_id = :id limit 1");
        $this->bind(':edit', $value);
        $this->bind(':id', $id);    
        $this->executeBind();
    }


    public function get_answer(string $question_id, array $saved_answers): string {
        if(!empty($saved_answers)) {
            foreach($saved_answers as $saved_answer) {
                if($saved_answer->question_id == $question_id) {
                    return $saved_answer->answer;
                }
            }
        }
        return '';
    }


    public function get_answered_percentage(array $questions, array $saved_answers) {
        $total_count_answer = 0;

        // looping through question IDs and getting answers
        if(!empty($questions)) {
            foreach($questions as $quest) {
                $answer = $this->get_answer($quest->id, $saved_answers);
                if(trim($answer) != '') {
                    $total_count_answer++;
                }
            }
        }

        // Getting percantage of questions
        if($total_count_answer > 0) {
            return round((($total_count_answer / count($questions)) * 100), 2);
        }

        return 0;
    }


    public function get_submited_test(array $data, $year): array {
        //show($data); //die;
        //checking if date column exist and answer column exist
        unset($data['answer']);
        unset($data['date']);
        
        $str = ' ';
        foreach($data as $key => $value) {
            $str .= $key . '=:' . $key . '&&';
        }

        $str = trim($str, '&&');

    //show($data);

        $this->query("SELECT * FROM submited_test WHERE $str AND year(date) = $year ORDER BY id DESC");
        $this->execute($data); 
        $data = $this->resultSet();
 
        if(is_array($data)) {
            foreach($this->afterSelect as $func) {
                $data = $this->$func($data);
            }
            return $data;
        }
        return [];
    }


    public function submited_test(string $user_id = NULL, $year, $school_id = NULL, string $table = NULL, int $marked = 0, int $submited = 1, int $status = 0): array {
        $table = is_null($table) ? 'class_lecturers' : $table;
        $this->query("SELECT * FROM submited_test WHERE test_id in (SELECT test_id FROM tests WHERE user_id IN (SELECT user_id FROM $table WHERE (user_id = :user_id || school_id = :skl) AND status = :status)) AND submitted = :sub AND marked = :mark AND year(date) = :year ORDER BY date desc");

        $this->bind(':skl', $school_id);
        $this->bind(':status', $status);
        $this->bind(':mark', $marked);
        $this->bind(':sub', $submited);
        $this->bind(':user_id', $user_id);
        $this->bind(':year', $year);
        
        $result = $this->resultSet();

        if(is_array($result)) {
            if(property_exists($this, 'afterSelect')) {
                foreach($this->afterSelect as $func) {
                    $result = $this->$func($result);
                }
            }
        }

        if(is_array($result) && count($result) > 0) {
            return $result;
        }

        return [];

    }


    public function get_mark_count() {
       // $class_test = [];           // Variable to hold the number of Tests found
        if(Auth::access('admin')) {         // This condition sets admin to see all Tests registered
            $class_test = $this->where('school_id', Auth::getSchool_id()); // fetching all test by school ID 
        } else {    
            // Reading class_lecturer to get class
            $year = !empty($_SESSION['USER']->year) ? $_SESSION['USER']->year : date("Y", time());
            $class_lect = $this->fetchClass(Auth::getUser_id(), 'class_lecturers', $year);
            //show($class_lect); 
            // Read test model to get test_id
            if($class_lect) {
                foreach($class_lect as $key => $value) {
                    // getting test data
                    $class_test = $this->where('user_id', $value->user_id);
                }
            }
        }

       if(isset($class_test)) {
            $class_test = array_column($class_test, 'test_id');
       }
      
      // show($class_test); die;

        // Read submited_test to get test that are submitted
        $to_mark = [];  
        if(isset($class_test)) {
            if(count($class_test) > 0) {
                $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
                foreach($class_test as $key => $value) {
                    //show($a); 
                    // getting test data
                    $summited_test[] = $this->get_submited_test(['submitted' => 1, 'marked' => 0, 'test_id' => $value], $year);
                }
            }
        } 
       
        //show($summited_test); die;

        if(isset($summited_test) && !empty($summited_test)) {
            foreach($summited_test as $key => $value) {
                if(empty($summited_test[$key])) {
                   // echo 'Yes'; 
                    unset($summited_test[$key]);
                }

                if(isset($summited_test[$key])) {
                    foreach($summited_test[$key] as $test_det => $value1) {
                        $test_detial = $this->where('test_id', $value1->test_id);
                        $summited_test[$key][$test_det]->test_detail = $test_detial[0];
                    }
                }
            }
            $to_mark = array_merge($to_mark, $summited_test);
            
            foreach($to_mark as $mark => $mk) {
                foreach($to_mark[$mark] as $key3 => $val3) {
                    $count[] = get_object_vars($val3);
                }
            }
        }

        if(!empty($count)) {
            return count($count);
        }

        return false;
    }

}