<?php
/*
** Profile Controller
*
*/

use app\core\Controller;

class Profile extends Controller {
    protected $userModel;
    protected $testModel;
    protected $classModel;
    protected $crumbs = [];


    public function __construct() {
        //echo get_class($this); die;
        if(!Auth::logged_in()) {
            $this->redirect('login');
        }
        $this->userModel = new User();
        $this->testModel =  new TestM();
        $this->classModel = new ClassM();

    }

    public function index($id = '') {
        $data = [];
        $id = trim($id == '') ? Auth::getUser_id() : $id;  
        $result = $this->userModel->where('user_id', $id);
        $result = $result[0];
        $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
        //show($row); die;

        $user_class = []; 

        $this->crumbs = [
            ['Dashboard', ''],
            ['Staffs', 'users'],
         ];

        if($result) {
            $profile = 'profile/' . $result->user_id;
            $this->crumbs[] =  [$result->firstname, $profile];
        }

        // switching tabs
        $page_tab = isset($_GET['tab']) ? $_GET['tab'] : 'info';

        if($page_tab == 'classes' && $result) { 
            if(Auth::access('admin')) {         // This condition sets admin to see all classes registered
                // If thiers a get request method in the url
                if(isset($_GET['find'])) {
                    $find = '%'. $_GET['find'] .'%' ;
                    $user_class = $this->classModel->find_all_class(Auth::getSchool_id(), $find, $year); // fetching all class by school ID and search request
                } else {
                    $user_class = $this->classModel->find_all_classes(Auth::getSchool_id(), $year); // fetching all class by school ID 
                    
                }

            } elseif(Auth::getRank() == 'lecturer') {
                // Instance of class model to get class lecturer/student classes
                $class = new ClassM();
                $class_combination = $this->classModel->fetchClass(Auth::getUser_id(), $year, 'class_lecturers');
                //show($class_combination); 

                $classes_i_own = $this->classModel->where('user_id', Auth::getUser_id());
                //show($classes_i_own);  die;
                if($classes_i_own || $class_combination) {
                    $class_combination = array_merge($class_combination, $classes_i_own);
                }

                $class_combination = array_column($class_combination, 'class_id');
                $class_combination = array_unique($class_combination); 
            
                // show($class_combination); 
                // get classes created by a specific lecturer or classes that don't have members
                
                $user_class = [];     // array to stores the number of users class fpound
                if(is_array($class_combination)) {
                    foreach($class_combination as $key) {
                        // getting class data
                        $user_class[] = $this->classModel->row_exist(['class_id'=> $key], null, 'single');
                        foreach($user_class as $user => $val) {
                            $user_class[$user]->user = (new User())->row_exist(['user_id' => $user_class[$user]->user_id], null, 'single');
                        }  
                    }
                }       

            } else {
                //$class = new ClassM();
                $class_combination = $this->classModel->fetchClass(Auth::getUser_id(), $year);
                $class_combination = array_column($class_combination, 'class_id');
                //show($class_combination); 
                //show($class_combination); die;
                // If theirs is a search through the get request 
                $user_class = [];     // array to stores the number of users class found
                if(is_array($class_combination)) {
                    foreach($class_combination as $key) {
                        // getting class data
                        $user_class[] = $this->classModel->row_exist(['class_id'=> $key], null, 'single');
                        foreach($user_class as $user => $val) {
                            $user_class[$user]->user = (new User())->row_exist(['user_id' => $user_class[$user]->user_id], null, 'single');
                        }  
                    }
                }
            }

        } elseif($page_tab == 'test' && $result) { 
           //show($result); die;
            
            if($result->rank == 'student') {
                $marked = [];    
                // getting test data
                $submitted_test = $this->testModel->get_submited_test(['submitted' => 1, 'marked' => 1, 'user_id' => $id], $year);
                // show($submitted_test); die;
                if(is_array($submitted_test) && !empty($submitted_test)) {
                    foreach($submitted_test as $key => $value) {
                        $test_detial = $this->testModel->where('test_id', $submitted_test[$key]->test_id);
                        $submitted_test[$key]->test_detail = $test_detial[0];
                        $marked_by = $this->userModel->where('user_id', trim($submitted_test[$key]->marked_by));
                        $submitted_test[$key]->marked_by = $marked_by[0];
                    }
                    $marked = array_merge($marked, $submitted_test);
                    $marked = array_unique($marked, SORT_REGULAR);
                    //show($marked); die;
                    $data['test_rows'][] = $marked;
                }    

            } elseif(Auth::getRank() == 'lecturer') {

                // // getting class results for lecturer
                // //$class_lect = $this->testModel->fetch(Auth::getUser_id(), 'class_lecturers');
                $data['test_rows'] = $this->testModel->find_test(Auth::getUser_id(), $year, 'class_lecturers', NULL);
                if(isset($_GET['find'])) {
                    $find = '%'. $_GET['find'] .'%' ;
                    $data['test_rows'] = $this->testModel->find_test_cl(Auth::getUser_id(), $year, 'class_lecturers', NULL, $find); // fetching test if search request
                   
                }

               // show($data['test_rows']); die;

                // // array to stores the number of users test fpound
                // if($class_lect) {
                //     foreach($class_lect as $key => $value) {
                //         // If theirs is a search through the get request 
                //         if(isset($_GET['find'])) {
                //             //show($class_lect); die;
                //             $class_lect = $class_lect[0];
                //             $find = '%'. $_GET['find'] .'%' ;
                //             $tests = $this->testModel->find_test_cl($class_lect->class_id, $find); // fetching all test by school ID and search request
                //             foreach($tests as $key => $value) {
                //                 if($tests[$key]->user_id == Auth::getUser_id()) {
                //                     $data['test_rows'][] = $tests[$key];
                //                 }
                //             }
                            
                //         } else {
                //             // getting test data
                //             $tests = $this->testModel->row_exist(['class_id' => $value->class_id]);
                //             //show($tests); die;
                //             foreach($tests as $key => $value) {
                //                 if($tests[$key]->user_id == Auth::getUser_id()) {
                //                     $data['test_rows'][] = $tests[$key];
                //                 }
                //             }
                //         }
                //     }
                // } elseif(Auth::access('admin')) {         // This condition sets admin to see all Tests registered
                //     $data['test_rows'] = $this->testModel->where('school_id', Auth::getSchool_id()); // fetching all test by school ID  
                // }
            } else {
                $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());
                $data['test_rows'] = $this->testModel->find_test_skl2(Auth::getSchool_id(), $year); // fetching all test by school ID  
                
            }
        }

        // Data sent to the view
        $data['rows'] = $result;
        $data['user_class'] = $user_class;
        $data['page_tab'] = $page_tab;
        $data['crumbs'] = $this->crumbs;

        if(Auth::access('reception') || Auth::i_own_content($result)) {
            $this->view('profile', $data);
        } else {
            $this->view('access-denied');
        }
    }


    public function edit($id = '') {

        $id = trim($id == '') ? Auth::getUser_id() : $id;

        if($_SERVER['REQUEST_METHOD'] == 'POST' && Auth::access('reception')) {
            
            // check if password exist
            if(trim($_POST['password']) == '') {
                unset($_POST['password']);
                unset($_POST['confirm_password']);
            }


            // something was posted
            //show($_POST); die;
            if($this->userModel->validate($_POST, $id)) {
    
                if($_POST['rank'] == 'super_admin' && $_SESSION['USER']->rank != 'super_admin') {
                    $_POST['rank'] = 'admin';
                }


                //show($_POST); die;
                //echo $id; die;
                $row = $this->userModel->where('user_id', $id);
                $row = $row[0];
                //show($row); die;
                //echo $row->id; die;

                // image to upload
                $my_image = upload_image($_FILES);
            
                // condition to check if old if image is upload
                if($my_image) {
                    $_POST['image'] = $my_image;
                    $old_img = $row->image;
                    unlink($old_img);
                } else {
                    $_POST['image'] = $row->image;
                }
                

                $this->userModel->update($row->id, $_POST);

                $redirect = 'profile/edit/'.$id; 
                $this->redirect($redirect);

            } else {
                // Errors
                $errors = $this->userModel->errors;
                $data['errors'] = $errors;
              
            }
        }

        $row = $this->userModel->where('user_id', $id);
        $result = $row[0];
        $data['rows'] = $result;

        
        if(Auth::access('admin') || (Auth::access('reception') && $result->rank == 'student')) {
            $this->view('profile-edit', $data);
        } else {
            $this->view('access-denied');
        }
    }
}