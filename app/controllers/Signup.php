<?php
/*
** Signup Controller
*
*/

use app\core\Controller;

class Signup extends Controller {

    public function __construct() {
         // echo 'Hpome Controller';
    }
    
    public function index() {

        // echo '<pre>';
        // print_r($_POST);
        // echo '</pre>';
        $data = [];
        $errors = [];
        $mode = isset($_GET['mode']) ? $_GET['mode'] : '';

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Instantiating User model
            $user = new User();

            if($user->validate($_POST)) {
                
                $_POST['date'] = date("Y-m-d H:i:s");

                if(Auth::access('reception')) {

                    if($_POST['rank'] == 'super_admin' && $_SESSION['USER']->rank != 'super_admin') {
                        $_POST['rank'] = 'admin';
                    }
                    
                    $user->insert($_POST);
                }

                if($mode == 'students') {
                    $this->redirect('students');
                }
                
                $this->redirect('users');

            } else {
                // Errors
                $errors = $user->errors;
                
                
                // Data to be sent to view
                $data = [
                    'errors' => $errors,
                ];
                
            }
        } 

         // Mode for cancel button in the signup view
       
        $data['mode'] = $mode;

        if(Auth::access('reception')) {
            $this->view('signup', $data);
        } else {
            $this->view('access-denied');
        }
        

    }
}