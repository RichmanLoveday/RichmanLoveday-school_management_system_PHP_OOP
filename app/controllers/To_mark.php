<?php
/*
** To_mark Controller
*
*/

use app\core\Controller;
class To_mark extends Controller {

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
        //$class_test = [];           // Variable to hold the number of Tests found
        if(Auth::access('admin')) {         // This condition sets admin to see all Tests registered
            $class_test = $this->testModel->submited_test(NULL, $year, Auth::getSchool_id()); // fetching all test by school ID 
        } else {    

            $class_test = $this->testModel->submited_test(Auth::getUser_id(), $year);

            // Reading class_lecturer to get class
            //$class_lect = $this->testModel->fetchClass(Auth::getUser_id(), 'class_lecturers');
            //show($class_lect); 

            // show($class_test); die;
            // Read test model to get test_id
            // if($class_lect) {
            //     foreach($class_lect as $key => $value) {
            //         // getting test data
            //         $class_test = $this->testModel->where('user_id', $value->user_id);

            //         // If theirs is a search through the get request 
            //         if(isset($_GET['find'])) {
            //             $class_lect = $class_lect[0];
            //             $find = '%'. $_GET['find'] .'%' ;
            //             $class_test[] = $this->testModel->find_test_cl($value->class_id, $find); // fetching all test by school ID and search request
            //         } 
            //     }
            // }
        }

       //$class_test = array_column($class_test, 'test_id');
      //show($class_test); die;

        // Read submited_test to get test that are submitted
        // $to_mark = [];   
        // if(count($class_test) > 0) {
        //     foreach($class_test as $key => $value) {
        //         //show($a); 
        //         // getting test data
        //         $summited_test[] = $this->testModel->get_submited_test(['submitted' => 1, 'marked' => 0, 'test_id' => $value]);
        //     }
        // }

        // show($summited_test); die;

        if(isset($class_test) && !empty($class_test)) {
            foreach($class_test as $key => $value) {
                $test_detial = $this->testModel->where('test_id', $value->test_id);
                $class_test[$key]->test_detail = $test_detial[0];
            }
            $to_mark = [];
            $to_mark = array_merge($to_mark, $class_test);
            //show($to_mark); die;
        }
        // crumbs
        $crumbs = [
            ['Dasboard', ''],
            ['To_mark', 'to_mark'],
        ];

        $data = [
            'test_rows' => isset($to_mark) ? $to_mark : '',
            'crumbs' => $crumbs,
        ];
        $this->view('to_mark', $data);
    }
}

