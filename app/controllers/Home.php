<?php
/*
** Home Controller
*
*/

use app\core\Controller;

class Home extends Controller {

    public $userModel;
    public function __construct() {
         // echo 'Hpome Controller';
         $this->userModel = new User();
         
         if(!Auth::logged_in()) {
            $this->redirect('login');
         }
    }


    public function index() {
       
        $result = $this->userModel->findAll();
        $data = [
            'rows' => $result,
        ];

        $this->view('Home', (array) $data);
    }
}