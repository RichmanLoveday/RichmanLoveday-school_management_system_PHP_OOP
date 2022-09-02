<?php
/*
** Single_class Controller
*
*/
declare(strict_types=1);
use app\core\Controller;

class Single_class extends Controller {
    protected $classModel;
    protected $crumbs = [];
    protected $result = '';
    protected $page_tab = '';
    protected $profile = '';
    protected $results = '';
    protected $userModel;
    protected $lectClassM;
    protected $studentClassM;
    protected $testModel;
    protected $lecturers = '';
    protected $students = '';
    protected $tests = '';

    public function __construct() {
        if(!Auth::logged_in()) {
            $this->redirect('login');
        }
        $this->lectClassM = new class_lecturer();
        $this->classModel = new ClassM();
        $this->userModel = new User();
        $this->studentClassM = new Class_student();
        $this->testModel = new TestM();
    }

    // Default method to display single class, view students, lecturers and tests
    public function index($id = '') {
        $errors = [];
        $this->result = $this->classModel->where('class_id', $id);
        $data['row'] = $this->result;
        $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
        // echo '<pre>';
        // print_r($result);
        // die();

        $this->crumbs = [
            ['Dasboard', ''],
            ['Classes', 'classes'],
        ];
        $data['crumbs'] = $this->crumbs;

        if($this->result) {
            $this->profile = 'single_class/' . $this->result[0]->class_id;
            $this->crumbs[] = [$this->result[0]->class, $this->profile];
        }
        $data['row'] = $this->result;

        // for pagination
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;
        $data['pager'] = $pager;

        $this->page_tab = isset($_GET['tab']) ? $_GET['tab'] : 'lecturers';
        $data['page_tab'] = $this->page_tab;

        if($this->page_tab == 'lecturers') {
            
            // Finding selcted lectureres from the lecturer and class that are not disabled
            $this->lecturers = $this->lectClassM->fetch_lecturers($id, $limit, $offset);
            // show($this->lecturers);
            // die;  
            $data['lecturers'] = $this->lecturers;
        } elseif($this->page_tab == 'students') {
            $this->students = $this->studentClassM->fetch_students($id, $limit, $offset);
            $data['students'] = $this->students;
            
        } elseif($this->page_tab == 'tests') {

            if(Auth::getRank() == 'lecturer') {
                $class_lect = $this->testModel->fetchClass(Auth::getUser_id(), 'class_lecturers', $year);
                // array to stores the number of users test fpound
                if($class_lect) {
                    foreach($class_lect as $key => $value) {
                        // If theirs is a search through the get request  
                        if(isset($_GET['find'])) {

                            //show($class_lect); die;
                            $class_lect = $class_lect[0];
                            $find = '%'. $_GET['find'] .'%' ;
                            $tests = $this->testModel->find_test_cl($class_lect->class_id, $find); // fetching all test by school ID and search request
                            $this->redirect('single_class/'.$id .'?tab=tests');
                            foreach($tests as $key => $value) {
                                if($tests[$key]->user_id == Auth::getUser_id()) {
                                    $data['test_rows'][] = $tests[$key];
                                }
                            }
                           
                        } else {
                            // getting test data
                            $tests = $this->testModel->where('class_id', $id);
                            if(isset($tests)) {
                                foreach($tests as $key => $value) {
                                    if($tests[$key]->user_id == Auth::getUser_id()) {
                                        $data['tests'][] = $tests[$key];
                                    }
                                }
                            }
                        }
                    }
                    if(isset($data['tests'])) {
                        $data['tests'] = array_unique($data['tests'], SORT_REGULAR);
                    }   
                }
            } elseif(Auth::access('admin')) {
                // If thiers a get request method in the url
                if(isset($_GET['find'])) {
                    $find = '%'. $_GET['find'] .'%' ;
                    $data['tests'] = $this->testModel->find_test_cl($id, $find); // fetching all test by school ID and search request
                    $this->redirect('single_class/'.$id .'?tab=tests');
                } else {
                    $data['tests'] = $this->testModel->find_test_skl2(Auth::getSchool_id(), $year); // fetching all test by school ID  
                    //show($user_test); die;
                    //show($data['tests']); die;
                }
            }
        }
        // Send data to the view
        $this->view('single-class', $data);
    }



    public function lecturersadd($id = '') {

        $errors = [];
        $this->result = $this->classModel->where('class_id', $id);
        $data['row'] = $this->result;
        // echo '<pre>';
        // print_r($result);
        // die();

        $this->crumbs = [
            ['Dasboard', ''],
            ['Classes', 'classes'],
        ];

        if($this->result) {
            $this->profile = 'single_class/' . $this->result[0]->class_id;
            $this->crumbs[] =  [$this->result[0]->class, $this->profile];
        }

        $this->page_tab = 'lecturers-add';
        $data['page_tab'] = $this->page_tab;

        // for pagination
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // show($_POST);
            // die();
            
            // search lecturer
            if(isset($_POST['search'])) {
                if(empty(trim($_POST['name']))) {
                    $errors[] = 'Pls enter a lecturer name';
                } else {
                    $lecturer_name = '%' . trim($_POST['name']) .'%';
                    $this->results = $this->userModel->search_lecturer($lecturer_name, Auth::getSchool_id(), $limit, $offset);
                    $data['results'] = $this->results;
                    
                }
            
            } elseif(isset($_POST['selected'])) {  
                // Check for lecturer existance in a specific class
                // Data to be sent to check for lecturer existance
                $arr = [
                    'user_id' => $_POST['selected'],
                    'class_id' => $id,
                ];

                // Condition to check if a Lecturer already exist in database
                $lect_exist = $this->lectClassM->lecturer_exist($arr);
                
                // getting the fist index of lecturer array
                $row = $lect_exist[0];
                
                // Condition to add lecturer
                if($lect_exist && $row->status == 1) {
                   
                    $lect_id = $row->id;
                    //show($id);
                    $data['status'] = 0;
                    $data['date'] = date("Y-m-d H:i:s");

                   // show($data); die;
                    $this->lectClassM->update($lect_id, $data);
                    $this->redirect('single_class/'.$id. '?tab=lecturers');

                }elseif($lect_exist && $row->status == 0) {      // If a specific lecturer is 0
                    // Return error when found
                    $errors[] = 'That lecturer already belong to this class';
                    
    
                } else {
                     // insert that lecturer to the database
                     $arr = [
                        'class_id' => $id,
                        'user_id' => $_POST['selected'],
                        'status' => 0,
                        'date' => date("Y-m-d H:i:s"),
                    ];
                    $this->lectClassM->insert($arr);
                    $this->redirect('single_class/'.$id. '?tab=lecturers');   
                }
            } 
        }

        // Send data to the view 
        //$data['lecturers'] = $this->lecturers;
        $data['errors'] = $errors;
        //show($data); die;
        if(Auth::access('lecturer')) {
            $this->view('single-class', $data);
        } else {
            $this->view('access-denied');
        }

    }


    // Method to remove lecturer
    public function lecturersremove($id = '') {
        $errors = [];
        $this->result = $this->classModel->where('class_id', $id);

        // echo '<pre>';
        // print_r($result);
        // die();

        $this->crumbs = [
            ['Dasboard', ''],
            ['Classes', 'classes'],
        ];

        if($this->result) {
            $this->profile = 'single_class/' . $this->result[0]->class_id;
            $this->crumbs[] =  [$this->result[0]->class, $this->profile];
        }

        $this->page_tab = 'lecturers-remove';
        
        // for pagination
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // show($_POST);
            // die();
            // search lecturer
            if(isset($_POST['search'])) {
                if(empty(trim($_POST['name']))) {
                    $errors[] = 'Pls enter a lecturer name';
                } else {
                    $lecturer_name = '%'. trim($_POST['name']) .'%';
                    $this->results = $this->userModel->search_lecturer($lecturer_name, Auth::getSchool_id(), $limit, $offset);
                }
            
            } elseif(isset($_POST['selected'])) {  
                // Check for lecturer existance in a specific class
                // Data to be sent to check for lecturer existance
                $data = [
                    'user_id' => $_POST['selected'],
                    'class_id' => $id,
                ];

                // Condition to check if a user a Lecturer user_id already exist in database
                $lect_exist = $this->lectClassM->lecturer_exist($data);

                if($lect_exist) {      // If a specific lecturer is found
                    // show($lect_exist);
                    // die();

                    // Getting the row ID returned
                    $lecturer_id = $lect_exist[0]->id;

                    // disable that specific lecturer by changing status to 1
                    $data = [
                        'status' => 1,
                    ];
        
                    $this->lectClassM->update($lecturer_id, $data);
                    $this->redirect('single_class/'.$id. '?tab=lecturers');
    
                } else {
                    // Return error when not found in this class
                    $errors[] = 'That lecturer was not found in this class';
                }

            } 

        }

        // Send data to the view
        $data = [
            'row' => $this->result,
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'lecturers' => $this->lecturers,
            'errors' => $errors,
            'pager' => $pager,
        ];

        if(Auth::access('lecturer')) {
            $this->view('single-class', $data);
        } else {
            $this->view('access-denied');
        }

    }


    // Method to add student
    public function studentsadd($id = '') {
        $errors = [];
        $this->result = $this->classModel->where('class_id', $id);

        // echo '<pre>';
        // print_r($result);
        // die();

        $this->crumbs = [
            ['Dasboard', ''],
            ['Classes', 'classes'],
        ];

        if($this->result) {
            $this->profile = 'single_class/' . $this->result[0]->class_id;
            $this->crumbs[] =  [$this->result[0]->class, $this->profile];
        }

        $this->page_tab = 'students-add';

        // for pagination
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;


        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // show($_POST);
            // die();
            // search student
            if(isset($_POST['search'])) {
                if(empty(trim($_POST['name']))) {
                    $errors[] = 'Pls enter a student name';
                } else {
                    $student_name = '%' . trim($_POST['name']) .'%';
                    $this->results = $this->userModel->search_student($student_name, Auth::getSchool_id(), $limit, $offset);
                    
                }
            
            } elseif(isset($_POST['selected'])) {  
                // Check for student existance in a specific class
                // Data to be sent to check for student existance
                $data = [
                    'user_id' => $_POST['selected'],
                    'class_id' => $id,
                ];

                // Condition to check if a user a student user_id already exist in database
                
                $stu_exist = $this->studentClassM->student_exist($data);
                $row = $stu_exist[0];
                // Condition to add student
                //show($row); die;
                if($stu_exist && $row->status == 1) {      // If a specific student is not found


                    $stu_id = $row->id;
                    //show($id);
                    $data['status'] = 0;
                    $data['date'] = date("Y-m-d H:i:s");

                   // show($data); die;
                    $this->studentClassM->update($stu_id, $data);
                    $this->redirect('single_class/'.$id. '?tab=students');

                }elseif($stu_exist && $row->status == 0) {      // If a specific lecturer is 0
                     // Return error when found
                     $errors[] = 'That student already belong to this class';
    
                } else {
                      // insert that student to the database
                    $data = [
                        'class_id' => $id,
                        'user_id' => $_POST['selected'],
                        'status' => 0,
                        'date' => date("Y-m-d H:i:s"),
                    ];
        
                    $this->studentClassM->insert($data);
                    $this->redirect('single_class/'.$id. '?tab=students');
    
                }
                    
            } 

        }

        // Send data to the view
        $data = [
            'row' => $this->result,
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'students' => $this->students,
            'errors' => $errors,
            'pager' => $pager,
        ];

        if(Auth::access('lecturer')) {
            $this->view('single-class', $data);
        } else {
            $this->view('access-denied');
        }
    }


    // Method to remove students
    public function studentsremove($id = '') {
        $errors = [];
        $this->result = $this->classModel->where('class_id', $id);

        // echo '<pre>';
        // print_r($result);
        // die();

        $this->crumbs = [
            ['Dasboard', ''],
            ['Classes', 'classes'],
        ];

        if($this->result) {
            $this->profile = 'single_class/' . $this->result[0]->class_id;
            $this->crumbs[] =  [$this->result[0]->class, $this->profile];
        }

        $this->page_tab = 'students-remove';

        // for pagination
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // show($_POST);
            // die();
            // search lecturer
            if(isset($_POST['search'])) {
                if(empty(trim($_POST['name']))) {
                    $errors[] = 'Pls enter a student name';
                } else {
                    $student_name = '%'. trim($_POST['name']) .'%';
                    $this->results = $this->userModel->search_student($student_name, Auth::getSchool_id(), $limit, $offset);
                }
            
            } elseif(isset($_POST['selected'])) {  
                // Check for lecturer existance in a specific class
                // Data to be sent to check for lecturer existance
                $data = [
                    'user_id' => $_POST['selected'],
                    'class_id' => $id,
                ];

                // Condition to check if a user a student user_id already exist in database
                $stu_exist = $this->studentClassM->student_exist($data);

                if($stu_exist) {      // If a specific student is found
                    // show($stu_exist);
                    // die();

                    // Getting the row ID returned
                    $row_id = $stu_exist[0]->id;
                    // show($student_id);
                    // die;
                    // disable that specific student by changing status to 1
                    $data = [
                        'status' => 1,
                    ];
        
                    $this->studentClassM->update($row_id, $data);
                    $this->redirect('single_class/'.$id. '?tab=students');
    
                } else {
                    // Return error when not found in this class
                    $errors[] = 'That student was not found in this class';
                }

            } 

        }

        // Send data to the view
         $data = [
            'row' => $this->result,
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'students' => $this->students,
            'errors' => $errors,
            'pager' => $pager,
        ];

        if(Auth::access('lecturer')) {
            $this->view('single-class', $data);
        } else {
            $this->view('access-denied');
        }

    }



    // Method to add test
    public function testadd($id = '') {
        $errors = [];
        $this->result = $this->classModel->where('class_id', $id);

        // echo '<pre>';
        // print_r($result);
        // die();

        $this->crumbs = [
            ['Dasboard', ''],
            ['Classes', 'classes'],
        ];

        if($this->result) {
            $this->profile = 'single_class/' . $this->result[0]->class_id;
            $this->crumbs[] =  [$this->result[0]->class, $this->profile];
        }

        $this->page_tab = 'test-add';

        // for pagination
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;


        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // show($_POST);
            // die();
            

            // Read post request and insert test into database
            if(isset($_POST['test'])) {  
                
                $data = [
                    'class_id' => $id,
                    'test' => $_POST['test'],
                    'description' => $_POST['description'],
                    'status' => 1,
                    'date' => date("Y-m-d H:i:s"),
                ];
    
                $this->testModel->insert($data);
                $this->redirect('single_class/'.$id. '?tab=tests');
    
                    
            } 

        }

        // Send data to the view
        $data = [
            'row' => $this->result,
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'students' => $this->students,
            'errors' => $errors,
            'pager' => $pager,
        ];

        $this->view('single-class', $data);

    }
   

    // Method to edit test
    public function testedit($id = '', $test_id = '') {
        $errors = [];
        $test_row = $this->testModel->where('test_id', $test_id);
        $test_row = $test_row[0];
        //show($test_row); die;
        $this->result = $this->classModel->where('class_id', $id);

        // echo '<pre>';
        // print_r($result);
        // die();

        $this->crumbs = [
            ['Dasboard', ''],
            ['Classes', 'classes'],
        ];

        if($this->result) {
            $this->profile = 'single_class/' . $this->result[0]->class_id;
            $this->crumbs[] =  [$this->result[0]->class, $this->profile];
        }

        $this->page_tab = 'test-edit';

        // for pagination
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;


        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // show($_POST);
            // die();
            
            // Read post request and insert test into database
            if(isset($_POST['test'])) {  
                
                $data = [
                    'test' => $_POST['test'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status'],
                ];

               // show($_POST); die;
    
                $this->testModel->update($test_row->id, $data);
                $this->redirect('single_class/testedit/'.$id. '/'. $test_id . '?tab=test-edit');
                     
            } 

        }

        // Send data to the view
        $data = [
            'row' => $this->result,
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'students' => $this->students,
            'errors' => $errors,
            'pager' => $pager,
            'test_row' => $test_row,
        ];

         if(Auth::access('lecturer')) {
            $this->view('single-class', $data);
        } else {
            $this->view('access-denied');
        }

    }



    // Method to delete test
    public function testdelete($id = '', $test_id = '') {
        $errors = [];
        $test_row = $this->testModel->where('test_id', $test_id);
        $test_row = $test_row[0];
        //show($test_row); die;
        $this->result = $this->classModel->where('class_id', $id);

        // echo '<pre>';
        // print_r($result);
        // die();

        $this->crumbs = [
            ['Dasboard', ''],
            ['Classes', 'classes'],
        ];

        if($this->result) {
            $this->profile = 'single_class/' . $this->result[0]->class_id;
            $this->crumbs[] =  [$this->result[0]->class, $this->profile];
        }

        $this->page_tab = 'test-delete';

        // for pagination
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;


        if($_SERVER['REQUEST_METHOD'] == 'POST') {
        //  show($_POST);
        //     die();
            
            // Read post request and insert test into database
            if(isset($_POST['test'])) {  
               // show($_POST); die;
    
                $this->testModel->delete($test_row->id);
                $this->redirect('single_class/'.$id.'?tab=tests');
                     
            } 

        }

        // Send data to the view
        $data = [
            'row' => $this->result,
            'crumbs' => $this->crumbs,
            'page_tab' => $this->page_tab,
            'results' => $this->results,
            'students' => $this->students,
            'errors' => 'Are you sure you want to delete this test permanently..?',
            'pager' => $pager,
            'test_row' => $test_row,
        ];

         if(Auth::access('lecturer')) {
            $this->view('single-class', $data);
        } else {
            $this->view('access-denied');
        }

    }
    
}
