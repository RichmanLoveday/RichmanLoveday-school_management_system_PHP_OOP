<?php
/*
** Students Controller
*
*/

use app\core\Controller;

class Students extends Controller {

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
        
        $limit = 2;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        if(isset($_GET['find'])) {
            $find = '%' . $_GET['find'] . '%';
            $result = $this->userModel->find_student($school_id, $find, $limit, $offset);

        } else {

            // $page_num = (int) isset($_GET['page']) ?? '1';
            // $page_num = $page_num < 1 ? 1 : $page_num;

           // echo $offset; die;
            $result = $this->userModel->get_students($school_id, $limit, $offset);
        }

    
        $this->crumbs = [
            ['Dasboard', ''],
            ['Schools', 'schools'],
            ['Students', 'students'],
        ];

        $data = [
            'rows' => $result,
            'crumbs' => $this->crumbs,
            'pager' => $pager,
        ];

        if(Auth::access('reception')) {
           // show($data); die;
            $this->view('Students', $data);
        } else {
            $this->view('access-denied');
        }

        
    }
}