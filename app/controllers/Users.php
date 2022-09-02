<?php
/*
** Users Controller
*
*/

use app\core\Controller;

class Users extends Controller {

    protected $userModel;
    protected $crumbs = [];
    public function __construct() {
         // echo 'Hpome Controller';
         $this->userModel = new User();
         
         if(!Auth::logged_in()) {
            $this->redirect('login');
         }
    }


    public function index() {
       
        $school_id = Auth::getSchool_id();
        $rank = Auth::getRank();

        $limit = 4;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        if(isset($_GET['find'])) {
            $find = '%'. $_GET['find'] .'%' ;
            $result = $this->userModel->find_staff($school_id, $find, $limit, $offset);
        } else {
            $result = $this->userModel->get_staffs($school_id, $limit, $offset);
        }

        $this->crumbs = [
            ['Dasboard', ''],
            ['Schools', 'schools'],
            ['Staffs', 'users'],
         ];

        $data = [
            'rows' => $result,
            'crumbs' => $this->crumbs,
            'pager' => $pager,
        ];

        if(Auth::access('admin')) {
            $this->view('Users', $data);
        } else {
            $this->view('access-denied');
        }
       
    }
}