<?php
/*
** Marked Controller
*
*/

use app\core\Controller;

class Marked extends Controller {

    public $userModel;
    public $testModel;
    public function __construct() {
        $this->userModel = new User();
        $this->testModel = new TestM();

        if(!Auth::access('lecturer')) {
            $this->redirect('access_denied');
        }
    }

    public function index() {
        $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
        $class_test = [];           // Variable to hold the number of Tests found
        if(Auth::access('admin')) {         // This condition sets admin to see all Tests registered

            // If thiers a get request method in the url
            if(isset($_GET['find'])) {
                $find = '%'. $_GET['find'] .'%' ;
                $class_test = $this->testModel->find_test_skl(Auth::getSchool_id(), $find, $year); // fetching all test by school ID and search request
            } else {
                $class_test = $this->testModel->where('school_id', Auth::getSchool_id()); // fetching all test by school ID  
            }

            //show($user_test); die;
        } else {    

            // Reading class_lecturer to get class
            $class_lect = $this->testModel->fetchClass(Auth::getUser_id(), 'class_lecturers', $year);

            // If theirs is a search through the get request 
            if(isset($_GET['find'])) {
                $class_lect = $class_lect[0];
                $find = '%'. $_GET['find'] .'%' ;
                $class_test = $this->testModel->find_test_cl(Auth::getUser_id(), $year, 'class_lecturers', NULL, $find); // fetching all test by user_ID and search request
            } else {
                //show($class_lect); die;

                // Read test model to get test_id
                $class_test = [];     
                if(!empty($class_lect)) {
                    foreach($class_lect as $key => $value) {
                        // getting test data
                        // $a = $this->testModel->where('class_id', $value->class_id);
                        $a = $this->testModel->where('user_id', $value->user_id);
                    }
                }
                $a = isset($a) ? $a : [];
                if(is_array($class_test) && isset($class_test)) {
                    $class_test = array_merge($class_test, $a);
                }
               // show($class_test); die;
            }
        }

        // Read submited_test to get test that are submitted
        $marked = []; 
        //$a = [];   
        if(!empty($class_test)) {
            if(count($class_test) > 0) {
                foreach($class_test as $key => $value) {
                    // getting test data
                    $marked_test[] = $this->testModel->get_submited_test(['submitted' => 1, 'marked' => 1, 'test_id' => $value->test_id], $year);
                }
            }
        }
        

        if(isset($marked_test) && !empty($marked_test)) {
            foreach($marked_test as $key => $value) {
                if(empty($marked_test[$key])) {
                    // echo 'Yes'; 
                    unset($marked_test[$key]);
                }

                // getting test data
                if(isset($marked_test[$key])) {
                    foreach($marked_test[$key] as $marked_tes => $value1) {
                        $test_detial = $this->testModel->where('test_id', $value1->test_id);
                        $user_name = $this->userModel->where('user_id', trim($value1->marked_by));
                        $marked_test[$key][$marked_tes]->test_detail = $test_detial[0];
                        $marked_test[$key][$marked_tes]->marked_by = $user_name[0];
                    }
                }
            }
            $marked = array_merge($marked, $marked_test);
        }
        //show($marked); die;

        // crumbs
        $crumbs = [
            ['Dasboard', ''],
            ['marked', 'tests'],
        ];

        //show($class_stu); die;
        // show($user_test); die;
        $data = [
            'test_rows' => $marked,
            'crumbs' => $crumbs,
        ];

        $this->view('marked', (array) $data);
    }
    
}