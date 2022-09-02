<?php
/*
** Classes Controllersc
*
*/

use app\core\Controller;
class Classes extends Controller {

    public $classModel;
    public $schoolModel;
    public function __construct() {
         // echo 'Hpome Controller';
         $this->classModel = new ClassM();
         $this->schoolModel = new School();
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
            ['Classes', 'classes'],
        ];
        $school_id = Auth::getSchool_id();
        $year = !empty($_SESSION['SCHOOL_YEAR']->year) ? $_SESSION['SCHOOL_YEAR']->year : date("Y", time());

        $user_class = [];           // Variable to hold the number of classes found
        if(Auth::access('admin')) {         // This condition sets admin to see all classes registered

            // If thiers a get request method in the url
            if(isset($_GET['find'])) {
                $find = '%'. $_GET['find'] .'%' ;
                $user_class = $this->classModel->find_all_class($school_id, $find, $year); // fetching all class by school ID and search request
            } else {
                $user_class = $this->classModel->find_all_classes($school_id, $year); // fetching all class by school ID 
                
            }

            //show($user_class); die;
        } else {    

            // If not and admin or Create an instance of the class model
            $class = new ClassM();
            $class_combination = '';         // Variable holds hold row of class combination of user


            // switching rank between lecturer and students from the user table to to send to the class page
            if(Auth::getRank() == 'lecturer') {
                $class_combination = $class->fetchClass(Auth::getUser_id(), $year, 'class_lecturers');
                //show($class_combination); die;

                $classes_i_own = $this->classModel->where('user_id', Auth::getUser_id());
                //show($classes_i_own); 
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
                        $user_class[] = $class->row_exist(['class_id'=> $key], null, 'single');
                        foreach($user_class as $user => $val) {
                            $user_class[$user]->user = (new User())->row_exist(['user_id' => $user_class[$user]->user_id], null, 'single');
                        }  
                    }
                }       

            } else {
                $class_combination = $class->fetchClass(Auth::getUser_id(), $year);
                $class_combination = array_column($class_combination, 'class_id');
                //show($class_combination); 
                //show($class_combination); die;
                // If theirs is a search through the get request 
                $user_class = [];     // array to stores the number of users class found
                if(is_array($class_combination)) {
                    foreach($class_combination as $key) {
                        // getting class data
                        $user_class[] = $class->row_exist(['class_id'=> $key], null, 'single');
                        foreach($user_class as $user => $val) {
                            $user_class[$user]->user = (new User())->row_exist(['user_id' => $user_class[$user]->user_id], null, 'single');
                        }  
                    }
                }
                //show($user_class); die;
            }
        }
       
        //show($user_class); die;
        $data = [
            'rows' => $user_class,
            'crumbs' => $crumbs,
        ];

        $this->view('Classes', (array) $data);
    }


    public function add() {

        $errors = [];
        $crumbs = [
            ['Dasboard', ''],
            ['Classes', 'classes'],
            ['Add', 'classes/add'],
        ];

        // Data to send to view
        $data = [
            'crumbs' => $crumbs,
        ];
        
        

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Instantiating class model

            if($this->classModel->validate($_POST)) {
                
                $_POST['date'] = date("Y-m-d H:i:s");

                $this->classModel->insert($_POST);
                $this->redirect('classes');

            } else {
                // Errors
                $errors = $this->classModel->errors;

                // Data to be sent to view
                $data['errors'] = $errors;                
            }
        }  

        $this->view('classes.add', (array) $data);
    }


    public function edit($id = null) {

        $errors = [];
        $data = [];
        $crumbs = [
            ['Dasboard', ''],
            ['classes', 'classes'],
            ['Edit', 'classes/edit'],
        ];
        

        if($_SERVER['REQUEST_METHOD'] == 'POST' && Auth::access('lecturer')) {
            
            // Instantiating class model
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';
            // die();
            if($this->classModel->validate($_POST)) {
                
                $this->classModel->update($id, $_POST);
                $this->redirect('classes');

            } else {
                // Errors
                $errors = $this->classModel->errors;
                
            }
        } 
        // Getting class name to display if no on the input field
        $row = $this->classModel->getclass($id);

         // Data to be sent to view
         $data = [
            'errors' => $errors,
            'class' => $row,
            'crumbs' => $crumbs,
        ];


        if(Auth::access('lecturer') && Auth::i_own_content($row)) {
            $this->view('classes.edit', (array) $data);
        } else {
            $this->view('access-denied');
        }
    }


    public function delete($id = "") {

        $errors = [];
        $data = [];
        $crumbs = [
            ['Dasboard', ''],
            ['classes', 'classes'],
            ['Delete', 'classes/delete'],
        ];
        

        if($_SERVER['REQUEST_METHOD'] == 'POST' && Auth::access('lecturer')) {
            
            // Instantiating class model
            //print_r($_POST);
            
            $this->classModel->delete($id);
            $this->redirect('classes');
           
        }
        
        $result = $this->classModel->where('id', $id);
        //show($result); die;
        // Data to be sent to view
        $data = [
            'result' => $result,
            'crumbs' => $crumbs,
        ];

        if(Auth::access('lecturer') && Auth::i_own_content($result)) {
            $this->view('classes.delete', (array) $data);
        } else {
            $this->view('access-denied');
        }
       
    }
}