<?php
/*
** Home Controllersc
*
*/

use app\core\Controller;

class Schools extends Controller {

    public $schoolModel;
    public function __construct() {
        // echo 'Hpome Controller';   
        $this->schoolModel = new School();
        
        if(!Auth::logged_in()) {
        $this->redirect('login');
        }
    }


    public function index() {
       
        $crumbs = [
            ['Dasboard', ''],
            ['Schools', 'schools'],
        ];
       
        $result = $this->schoolModel->findAll();
        $data = [
            'rows' => $result,
            'crumbs' => $crumbs,
        ];

        if(Auth::access('super_admin')) {
            $this->view('Schools', (array) $data);
        } else {
            $this->view('access-denied');
        }
    }


    public function add() {

        $errors = [];
        $crumbs = [
            ['Dasboard', ''],
            ['Schools', 'schools'],
            ['Add', 'schools/add'],
        ];

        // Data to send to view
        $data = [
            'crumbs' => $crumbs,
        ];
        
        

        if($_SERVER['REQUEST_METHOD'] == 'POST' && Auth::access('super_admin')) {
            
            // Instantiating School model

            if($this->schoolModel->validate($_POST)) {
                
                $_POST['date'] = date("Y-m-d H:i:s");

                $this->schoolModel->insert($_POST);
                $this->redirect('schools');

            } else {
                // Errors
                $errors = $this->schoolModel->errors;

                // Data to be sent to view
                $data['errors'] = $errors;                
            }
        } 
        if(Auth::access('super_admin')) {
            $this->view('school.add', (array) $data);
        } else {
            $this->view('access-denied');
        }
       
    }


    public function edit($id = null) {

        $errors = [];
        $data = [];
        $crumbs = [
            ['Dasboard', ''],
            ['Schools', 'schools'],
            ['Edit', 'schools/edit'],
        ];
        

        if($_SERVER['REQUEST_METHOD'] == 'POST' && Auth::access('super_admin')) {
            
            // Instantiating School model
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';
            // die();
            if($this->schoolModel->validate($_POST)) {
                
                $this->schoolModel->update($id, $_POST);
                $this->redirect('schools');

            } else {
                // Errors
                $errors = $this->schoolModel->errors;
                
            }
        } 

        // Getting school name to display if no on the input field
        $row = $this->schoolModel->getSchool($id);

         // Data to be sent to view
         $data = [
            'errors' => $errors,
            'school' => $row,
            'crumbs' => $crumbs,
        ];

        if(Auth::access('super_admin')) {
            $this->view('schools.edit', (array) $data);
        } else {
            $this->view('access-denied');
        }

    }


    public function delete($id = "") {

        $errors = [];
        $data = [];
        $crumbs = [
            ['Dasboard', ''],
            ['Schools', 'schools'],
            ['Delete', 'schools/delete'],
        ];
        

        if($_SERVER['REQUEST_METHOD'] == 'POST' && Auth::access('super_admin')) {
            
            // Instantiating School model
            //print_r($_POST);
            
            $this->schoolModel->delete($id);
            $this->redirect('schools');
           
        }
        
        $result = $this->schoolModel->where('id', $id);
        
        // Data to be sent to view
        $data = [
            'result' => $result,
            'crumbs' => $crumbs,
        ];

        if(Auth::access('super_admin')) {
            $this->view('schools.delete', (array) $data);
        } else {
            $this->view('access-denied');
        }
        
    }
}