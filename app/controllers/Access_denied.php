<?php
/*
** Login Controller
*
*/

declare(strict_types=1);

use app\core\Controller;
class Access_denied extends Controller {

    public function __construct() {
        // echo 'Hpome Controller';
        if(!Auth::logged_in()) {
            $this->redirect('login');
        }
    }
    public function index() {
        $this->view('access-denied');
    }
}