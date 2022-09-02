<?php
/*
** change school Controller
*
*/

use app\core\Controller;

class Switch_school extends Controller {

    public $switchSchoolModel;
    public function __construct() {
         // echo 'Hpome Controller';
         $this->userModel = new User();
        
    }

    public function index(int $id) {
        
        if(Auth::access('super_admin')) {
            Auth::switch_school($id);
        }
       
        $this->redirect('schools');
    }
}