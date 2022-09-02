<?php
/*
** Take Test Controller
*
*/
declare(strict_types=1);
use app\core\Controller;

class Mark_test extends Controller {
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
        if(!Auth::access('lecturer')) {
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

        // if something was posted
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //show($_POST); die;
            
            // checking if there are already sumitted answers
            foreach($_POST as $key => $value) {
                if(is_numeric($key)) {
                    //show($_POST); 
                    //checking if there are already sumitted answers
                    $row = $this->answersM->row_exist(['user_id' => $stu_id, 'test_id' => $test_id, 'question_id' => $key]);
                    if($row) {
                        // If row exist update answer_mark column
                        $row_id = $row[0]->id;
                        $this->answersM->update($row_id, ['answer_mark' => trim($value, ' ')]);
                    }
                }
            }

            $page_num = "&page=1";
            if(!empty($_GET['page'])) {
                $page_num = "&page=".$_GET['page'];
            }
            $this->redirect('mark_test/'.$test_id.'/' . $stu_id.$page_num);
        }


        // if test is summited
        if(isset($_GET['unsubmit'])) {
            $this->answersM->update_submitted_test(['test_id' => $test_id, 'user_id' => $stu_id], ['submitted' => 0, 'submitted_date' => '']);
        }

        // if get request is set as marked
        if(isset($_GET['set_marked']) && (get_marked_percentage($test_id, $stu_id) >= 100)) {
            $this->answersM->update_submitted_test(['test_id' => $test_id, 'user_id' => $stu_id], ['marked' => 1, 'marked_by' => trim(Auth::getUser_id()), 'score' => get_score_percentage($test_id, $stu_id), 'date_marked' => date('Y-m-d H:i:s')]);
            $this->redirect('mark_test/'.$test_id . '/' . $stu_id);
        }

        // if get request is set as marked
        if(isset($_GET['unsubmit'])) {
            $this->answersM->update_submitted_test(['test_id' => $test_id, 'user_id' => $stu_id], ['submitted' => 0, 'submitted_date' => ' ']);
        }


        // if it's an auto mark
        if(isset($_GET['auto_mark'])) {
            $original_question = $this->answersM->get_question_type($test_id);
            //show($original_question); die;
            if($original_question) {  
                foreach($original_question as $question_row) {
                    $answer_row = $this->testModel->row_exist(['user_id' => $stu_id, 'test_id' => $test_id, 'question_id' => $question_row->id], 'answers');

                    //show($answer_row);
                    if($answer_row) {
                        $answer_row = $answer_row[0];
                        $correct = strtolower(trim($question_row->correct_answer));
                        $student_answer = strtolower(trim($answer_row->answer));

                        // Updating the answer mark
                        if($correct == $student_answer) {
                            // this answer is correct
                            $this->answersM->update($answer_row->id, ['answer_mark' => 1]);
                        } else {
                            // answer is not correct
                            $this->answersM->update($answer_row->id, ['answer_mark' => 2]);
                        }
                    }
                }
            }
            // redirect to same page
            $page_num = "&page=1";
            if(!empty($_GET['page'])) {
                $page_num = "&page=".$_GET['page'];
            }
            $this->redirect('mark_test/'.$test_id.'/' . $stu_id.$page_num);
        }

        
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

        //show($student_row); die;
       

        //show($student_row); die;
                                                                                 
        // Send data to the view
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
        if(Auth::access('lecturer')) {
            $this->view('mark-test', $data);
        } else { 
            $this->view('access-denied');
        }
    } 
}
