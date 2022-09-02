<?php
/*
** Take Test Controller
*
*/
declare(strict_types=1);
use app\core\Controller;

class Take_test extends Controller {
    protected $classModel;
    protected $crumbs = [];
    protected $result = '';
    protected $questions = '';
    protected $page_tab = '';
    protected $results = '';
    protected $all_quest = '';
    
    protected $userModel;
    // protected $lectClassM;
    // protected $studentClassM;
    protected $testModel;
    protected $questionModel;
    protected $lecturers = '';
    protected $students = '';
    protected $tests = '';
    protected $answersM = '';
    public function __construct() {
        if(!Auth::logged_in()) {
            $this->redirect('login');
        }
        // $this->lectClassM = new class_lecturer();
        $this->classModel = new ClassM();
        $this->userModel = new User();
        // $this->studentClassM = new Class_student();
        $this->testModel = new TestM();
        $this->questionModel = new QuestionsM();
        $this->answersM = new AnswersM();
    }

    // Default method to display single class, view students, lecturers and tests
    public function index($id = '') {
        $errors = [];

        // for pagination
        $limit = 3;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $this->result = $this->testModel->where('test_id', $id);            // searching for test by ID
        $this->questions = $this->questionModel->where('test_id', $id, 'asc', $limit, $offset);          //searching and limiting questions
        $this->all_quest = $this->questionModel->get_all_quest($id);          //searching for all question
        $total_questions = is_array($this->all_quest) ? $this->all_quest : 0;
        //show($total_questions); die;
        // echo '<pre>';
        // print_r($result);
        // die();
       
    
        $this->crumbs = [
            ['Dasboard', ''],
            ['Test', 'tests'],
        ];

        if(is_array($this->result) && $this->result) {
            $this->profile = 'single_test/' . $this->result[0]->test_id;
            $this->crumbs[] = [$this->result[0]->test, $this->profile];
            //var_dump($this->result); die;
            $result = $this->result[0];
            $edit = 1;

            if(!$result->status) {
                $this->testModel->single_update($result->test_id, 'editable', $edit);
            }
        }
        
        $this->page_tab = 'view';

        // if something was posted
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //show($_POST); die;

            // checking if they are already sumited tests
            $arr1['user_id'] = Auth::getUser_id();
            $arr1['test_id'] = $id;

            $sub = $this->answersM->row_exist($arr1, 'submited_test');
            if(!$sub) {
                $arr1['date'] = date('Y-m-d H:i:s');
                $this->answersM->insert($arr1, 'submited_test');
            }
            
            // checking if there are already sumitted answers
            foreach($_POST as $key => $value) {
                if(is_numeric($key)) {
                    $arr['user_id'] = Auth::getUser_id();
                    $arr['test_id'] = $id;
                    $arr['question_id'] = $key;
                    $arr['date'] = date('Y-m-d H:i:s');
                    $arr['answer'] = trim($value);

                    //checking if there are already sumitted answers
                    $row = $this->answersM->row_exist($arr);
                    if(!$row) {
                        $this->answersM->insert($arr);

                    } else { 
                        $row_id = $row[0]->id;

                        //echo $row_id; 
                        unset($arr['user_id']);
                        unset($arr['test_id']);
                        unset($arr['question_id']);
                        unset($arr['date']);
                        
                        $this->answersM->update($row_id, $arr);
                    }
                }
            }
            $page_num = "&page=1";
            if(!empty($_GET['page'])) {
                $page_num = "&page=".$_GET['page'];
            }
            $this->redirect('take_test/'.$id.$page_num);
        }


        // if test is summited
        if(isset($_GET['submit'])) {
            $this->answersM->update_submitted_test(['test_id' => $id, 'user_id' => Auth::getUser_id()],  ['submitted' => 1, 'submitted_date' => date('Y-m-d H:i:s')]);
            $this->redirect('take_test/'.$id);
        }

        // getting saved questions from the data base   
        $saved_answer = $this->answersM->row_exist(['test_id' => $id, 'user_id' => Auth::getUser_id()]);
        //show($saved_answer); die;

        // Getting submited test row
        $arr1 = [];
        $arr1['user_id'] = Auth::getUser_id();
        $arr1['test_id'] = $id;
        $submited_test = $this->answersM->row_exist(['test_id' => $id, 'user_id' => Auth::getUser_id()], 'submited_test');
        $submited_test_row = is_array($submited_test) ? $submited_test[0] : '';
        
        // gettting the student information
        $student_row = $this->userModel->row_exist(['user_id' => Auth::getUser_id()], 'users');
        $student_row = $student_row[0];
        //show($student_row); die;
                                                                                 
        // Send data to the view
        $data = [
            'row' => $this->result[0],
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'lecturers' => $this->lecturers,
            'students' => $this->students,
            'questions' => $this->questions,
            'total_questions' => $total_questions,
            'errors' => $errors,
            'pager' => $pager,
            'saved_answers' => !empty($saved_answer) ? $saved_answer : [],
            'submited_test' => $submited_test_row,
            'student_row' => $student_row,
            //'submited_test_row' => $this->testModel->row_exist(['test_id' => $id, 'user_id' => Auth::getUser_id()], 'submited_test'),
        ];
        
        //show($this->questions); die;
        if(Auth::access('student')) {
            $this->view('take-test', $data);
        } else { 
            $this->view('access-denied');
        }
    } 


    // Method to add subjectives
    public function addquestion($id = '') {
        $errors = [];
        $this->result = $this->testModel->where('test_id', $id);

        // echo '<pre>';
        // print_r($result);
        // die();

        $this->crumbs = [
            ['Dasboard', ''],
            ['Test', 'tests'],
        ];

        if($this->result) {
            $this->profile = 'single_test/' . $this->result[0]->test_id;
            $this->crumbs[] = [$this->result[0]->test, $this->profile];
        }
        
        $this->page_tab = 'add-question';

        // for pagination
        $limit = 10;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //show($_POST); die;
            if($this->questionModel->validate($_POST)) {
                $_POST['date'] = date('Y-m-d H:i:s');
                $_POST['test_id'] = $id;

                // check for files
                $my_image = upload_image($_FILES);
                $_POST['image'] = $my_image ? $my_image : '';


                if(isset($_GET['type']) && $_GET['type'] == 'objective') {
                    $_POST['question_type'] = 'objective';
                } elseif(isset($_GET['type']) && $_GET['type'] == 'multiple') {
                    $_POST['question_type'] = 'multiple';
                    //show($_POST); die;
                    // For multiple questions
                    $letters = ['A', 'B', 'C', 'D', 'F', 'E', 'I', 'J'];
                    $arr = [];
                    $num = 0;
                    foreach($_POST as $key => $value) {
                        if(strstr($key, 'choice')) {
                            $arr[$letters[$num]] = $value;
                            //unset($_POST[$key]);
                            $num++;
                        }
                    }

                    $_POST['choices'] = json_encode($arr);
                    //show($_POST); die;

                } else {
                    $_POST['quetion_type'] = 'subjective';
                }

                // inserting question and redirecting
                $this->questionModel->insert($_POST);
                $this->redirect('single_test/'.$id);

            } else {
                // errors
                $errors = $this->questionModel->errors;
            }
        }

        // Send data to the view
        $data = [
            'row' => $this->result,
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'lecturers' => $this->lecturers,
            'students' => $this->students,
            'tests' => $this->tests,
            'errors' => $errors,
            'pager' => $pager,
        ];

        $this->view('single-test', $data);
    }
}
