<?php
/*
** Login Controller
*
*/

declare(strict_types=1);

use app\core\Controller;
class Login extends Controller {

    public function __construct() {
         // echo 'Hpome Controller';

         
    }
    public function index() {
        
        $data = [];
        $errors = [];

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Logining with email
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Instantiating User model
            $user = new User(); 
            $row = $user->where('email', $email);
            //show($row); die;
            if($row == true) {
                
               
                
                $row = $row[0];
                //show($row); die;

                // Verify password
                if(isset($row) && $row != NULL) {
                    //show($row); die;
                    if(password_verify($password, $row->password)) {
                        $school = new School;
                        $school->get_school_name($row);

                        Auth::authenticate($row);
                        $this->redirect('home');
                    }
                }
            } 
                
            $errors['email'] = 'Wrong Email or password';
        
        } 

        $data = [
            'errors' => $errors,
        ];

        $this->view('Login', $data);
    }
}