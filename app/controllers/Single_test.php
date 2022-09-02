<?php
/*
** Single test Controller
*
*/
declare(strict_types=1);
use app\core\Controller;

class Single_test extends Controller {
    protected $classModel;
    protected $crumbs = [];
    protected $result = '';
    protected $questions = '';
    protected $page_tab = '';
    protected $results = '';
    protected $userModel;
    // protected $lectClassM;
    // protected $studentClassM;
    protected $testModel;
    protected $questionModel;
    protected $lecturers = '';
    protected $students = '';
    protected $tests = '';

    public function __construct() {
        if(!Auth::logged_in()) {
            $this->redirect('login');
        }
        // $this->lectClassM = new class_lecturer();
        $this->classModel = new ClassM();
        $this->userModel = new User();
        // $this->studentClassM = new Class_student();
        $this->testModel = new TestM();
        $this->questionModel = new QuestionsM;
    }

    // Default method to display single class, view students, lecturers and tests
    public function index($id = '', $status = '') {
        $errors = [];
        $this->result = $this->testModel->where('test_id', $id);
        //show($this->result); die;
        $this->questions = $this->questionModel->where('test_id', $id);
        $total_questions = count($this->questions);
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

        if(isset($_GET['status']) && $_GET['status'] == 'true') {
            //echo $_GET['status']; die;
            $value = '';
            if($this->result[0]->status) {
                $value = 0;
            } else {
                //echo $this->result[0]->status; die;
                $value = 1;            
            }
            
            $this->testModel->single_update($id, 'status', $value);
            $this->redirect('single_test/'.$id);
        }

        $submitted_test = new Submitted_testM();
       // $this->page_tab = isset($_GET['tab']) ? ($student_scores = $submitted_test->row_exist(['test_id' => $id]) ? 'scores')  : 'view';

        $this->page_tab = 'view';
        $student_scores = $submitted_test->row_exist(['test_id' => $id, 'submitted' => 1, 'marked' => 1]);
        if(isset($_GET['tab'])) {
            $this->page_tab = 'scores';
            $student_scores = $submitted_test->row_exist(['test_id' => $id, 'submitted' => 1, 'marked' => 1]);
        }

        

        //show($student_scores); die;

        
        // for pagination
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        // Send data to the view
        $data = [
            'row' => $this->result,
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'lecturers' => $this->lecturers,
            'students' => $this->students,
            'questions' => $this->questions,
            'total_questions' => $total_questions,
            'errors' => $errors,
            'pager' => $pager,
            'student_scores' => isset($student_scores) ? $student_scores : '',
        ];

        if(Auth::access('lecturer')) {
            $this->view('single-test', $data);
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


    // Method to add subjectives
    public function editquestion($test_id = '', $quest_id = '') {
        //echo $test_id . ' ', $quest_id; die;
        $errors = [];
        $this->result = $this->testModel->where('test_id', $test_id);
        $question = $this->questionModel->where('id', $quest_id);
        $question = $question[0];
        //show($question); die;

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
        
        $this->page_tab = 'edit-question';

        // for pagination
        $limit = 10;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //show($_POST); die;

            if(!$this->result[0]->editable) {
                $errors[] = 'Editing for this question is disabled';
                //show($errors); die;
            }

            if($this->questionModel->validate($_POST) && count($errors) == 0) {
                $_POST['date'] = date('Y-m-d H:i:s');

                // image to upload
                $my_image = upload_image($_FILES);
                // $_POST['image'] = $my_image ? $my_image : $question->image;

                // condition to check if old if image is upload
                if($my_image) {
                    $_POST['image'] = $my_image;
                    $old_img = $question->image;
                    unlink($old_img);
                } else {
                    $_POST['image'] = $question->image;
                }

                
                if(isset($_GET['type']) && $_GET['type'] == 'multiple') {
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
                }
                $_POST['choices'] = json_encode($arr);
                   
                //show($_POST); die;
                
                // updating the questions 
                $this->questionModel->update($quest_id, $_POST);

                // changing the question types
               // echo $question->question_type;
                $type = '';
                if($question->question_type == 'objective') {
                    $type = '?type=objective';
                } elseif($question->question_type == 'multiple') {
                    $type = '?type=multiple';
                } else {
                    $type = '?type=subjective';
                }

                $this->redirect('single_test/editquestion/'.$test_id . '/' . $quest_id . $type);
            } else {
                // errors
                $errors = array_merge($errors, $this->questionModel->errors);
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
            'question' => $question,
        ];


        $this->view('single-test', $data);
    }



    // Method to add subjectives
    public function deletequestion($test_id = '', $quest_id = '') {
        //echo $test_id . ' ', $quest_id; die;
        $errors = [];

        $this->result = $this->testModel->where('test_id', $test_id);
        $question = $this->questionModel->where('id', $quest_id);
        $question = $question[0];
        //show($question); die;

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
        
        $this->page_tab = 'delete-question';

        // for pagination
        $limit = 10;
        $pager = new Pager($limit);
        $offset = $pager->offset;


        if(!$this->result[0]->editable) {
            $errors[] = 'This test question cannot be deleted';
            //show($errors); die;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            //show($_POST); die;

            if(Auth::access('lecturer')) {
                if(count($errors) == 0) {
                    $this->questionModel->delete($quest_id);
                    if(file_exists($question->image)) {
                        unlink($question->image);
                    }
                }
                $this->redirect('single_test/'.$test_id);
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
            'question' => $question,
        ];

        $this->view('single-test', $data);
    }
}
