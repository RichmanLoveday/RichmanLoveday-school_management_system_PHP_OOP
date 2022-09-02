<?php
/*
** Tests Controllersc
*
*/
use app\core\Controller;

Class Tests extends Controller {

    public $testModel;
    public $schoolModel;
    public $submittedTest;

    public function __construct() {
        // echo 'Hpome Controller';
        $this->testModel = new TestM();
        $this->schoolModel = new School();
        $this->submittedTest = new Submitted_testM();
        if(!Auth::logged_in()) {
            $this->redirect('login');
        }

        if(!Auth::access('lecturer') || !Auth::getRank() == 'student') {
            $this->redirect('access_denied');
        }
    }


    public function index() {
        $crumbs = [
            ['Dasboard', ''],
            ['Tests', 'tests'],
        ];
        $data['crumbs'] = $crumbs;
        $school_id = Auth::getSchool_id();

        // Variable to hold the number of Tests found
        if(Auth::access('admin')) {         // This condition sets admin to see all Tests registered

            // If thiers a get request method in the url
            if(isset($_GET['find'])) {
                $find = '%'. $_GET['find'] .'%' ;
                $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
                $data['test_rows'] = $this->testModel->find_test_skl($school_id, $find, $year); // fetching all test by school ID and search request
            } else {
                $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
                $data['test_rows'] = $this->testModel->find_test_skl2($school_id, $year); // fetching all test by school ID  
                
                //show($user_test); die;
            }

            //show($user_test); die;
        } else {    
            // If not and admin or Create an instance of the test model
            $class_lect = '';         // Variable holds hold row of test combination of user
            // switching rank between lecturer and students from the user table to to send to the test page
            if(Auth::getRank() == 'lecturer') {
                // getting class results for lecturer
                $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
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
                            foreach($tests as $key => $value) {
                                if($tests[$key]->user_id == Auth::getUser_id()) {
                                    $data['test_rows'][] = $tests[$key];
                                }
                            }
                            
                        } else {
                            // getting test data
                            $tests = $this->testModel->where('class_id', $value->class_id);
                            //show($tests); die;
                            foreach($tests as $key => $value) {
                                if($tests[$key]->user_id == Auth::getUser_id()) {
                                    $data['test_rows'][] = $tests[$key];
                                }
                            }
                        }
                    }
                }
                
            } else {
                // getting test for a specific student
                $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
                $data['test_rows'] = $this->testModel->find_test(Auth::getUser_id(), $year, NULL, NULL);
                $data['unsubmitted_test'] = $this->submittedTest->get_unsubmitted_rows();

                if(isset($_GET['find'])) {
                    $find = '%'. $_GET['find'] .'%' ;
                    $data['test_rows'] = $this->testModel->find_test_cl(Auth::getUser_id(), $year, NULL, NULL, $find); // fetching all test by school ID and search request
                }

                //show($class_stu); die;
                // If theirs is a search through the get request 
                // if(isset($_GET['find'])) {
                //     $class_stu = $class_stu[0];
                //     $find = '%'. $_GET['find'] .'%' ;
                //     $user_test = $this->testModel->find_test_cl($class_stu->class_id, $find); // fetching all test by school ID and search request
                //     $data['test_rows'] = $user_test;
                // } else {
                //     //show($class_stu); die;
                //     $user_test = [];     // array to stores the number of users test fpound
                //     if($class_stu) {
                //         foreach($class_stu as $key => $value) {
                //             // getting test data
                //             $a = $this->testModel->find_test($value->class_id);
                //             if(is_array($a)) {
                //                 $user_test = array_merge($user_test, $a);
                //                 $data['test_rows'] = $user_test;
                //             }
                //         }
                //     }
                // }
            }
        }
        $this->view('tests', (array) $data);
    }
}