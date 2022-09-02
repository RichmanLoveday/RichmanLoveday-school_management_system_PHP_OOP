<?php
/*
** Logout Controller
*
*/

use app\core\Controller;

class Logout extends Controller {

    public $userModel;
    public function __construct() {
         // echo 'Hpome Controller';
         $this->userModel = new User();
         
         Auth::logout();
         $this->redirect('login');
        
    }
}