<?php
/*
** Marked_single Controller
*
*/
declare(strict_types=1);
use app\core\Controller;

class Marked_single extends Controller {
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
        if(!Auth::access('student')) {
            $this->redirect('access_denied');
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
    public function index($test_id = '', $stu_id = '') {
        $errors = [];

        // for pagination
        $limit = 3;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $this->result = $this->testModel->where('test_id', $test_id);            // searching for test by ID
        $this->questions = $this->questionModel->where('test_id', $test_id, 'asc', $limit, $offset);          //searching and limiting questions
        $this->all_quest = $this->questionModel->get_all_quest($test_id);          //searching for all question
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

        //show($saved_answer); die;
        // Getting submited test row
        $arr1 = [];
        $arr1['user_id'] = $stu_id;
        $arr1['test_id'] = $test_id;
        $submited_test = $this->answersM->row_exist(['test_id' => $test_id, 'user_id' => $stu_id], 'submited_test');
        $submited_test_row = is_array($submited_test) ? $submited_test[0] : '';
        
        // gettting the student information
        if($submited_test_row) {
            $student_row = $this->userModel->row_exist(['user_id' => $stu_id], 'users');
            $student_row = $student_row[0];
        }


        // getting saved questions from the data base   
        $saved_answer = $this->answersM->row_exist(['test_id' => $test_id, 'user_id' => $stu_id]);

        $data = [
            'row' => isset($this->result[0]) ? $this->result[0] : '',
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'lecturers' => $this->lecturers,
            'students' => $this->students,
            'questions' => $this->questions,
            'total_questions' => $total_questions,
            'errors' => $errors,
            'pager' => $pager,
            'saved_answers' => $saved_answer,
            'submited_test' => isset($submited_test_row) ? $submited_test_row : '',
            'student_row' => isset($student_row) ? $student_row : '',
            //'submited_test_row' => $this->testModel->row_exist(['test_id' => $id, 'user_id' => Auth::getUser_id()], 'submited_test'),
        ];
        
        //show($this->questions); die;
        if(!Auth::logged_in()) {
            $this->view('login');
        } else { 
            $this->view('marked_single', $data);
        }
    } 
}
