<?php
/*
** make pdf Controller
*
*/

use app\core\Controller;

class Make_pdf extends Controller {

    // public $userModel;
    // public $testModel;
    // public $classModel;
    // public $questionsModel;
    // public $answerM;
    
    // public function __construct() {
    //     // echo 'Hpome Controller';
    //     $this->classModel = new ClassM();
    //     $this->userModel = new User();
    //     // $this->studentClassM = new Class_student();
    //     $this->testModel = new TestM();
    //     $this->questionModel = new QuestionsM();
    //     $this->answersM = new AnswersM();
        
    //     if(!Auth::logged_in()) {
    //     $this->redirect('login');
    //     }
    // }

    protected $classModel;
    protected $crumbs = [];
    protected $result = '';
    protected $page_tab = '';
    protected $results = '';
    protected $all_quest = '';
    
    protected $userModel;
    // protected $lectClassM;
    // protected $studentClassM;
    protected $testModel;
    protected $questionModel;
    protected $lecturers = '';
    protected $students = '';
    protected $tests = '';
    protected $answersM = '';
    public function __construct() {
        if(!Auth::logged_in()) {
            $this->redirect('login');
            }
        // $this->lectClassM = new class_lecturer();
        $this->classModel = new ClassM();
        $this->userModel = new User();
        // $this->studentClassM = new Class_student();
        $this->testModel = new TestM();
        $this->questionModel = new QuestionsM();
        $this->answersM = new AnswersM();
    }


    public function index($test_id = '', $stu_id = 'tom.cruise') {
       // mpdf.. A php libray to export a file to pdf format.
        $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new \Mpdf\Mpdf([
            'fontdata' => $fontData + [
                'roboto' => [
                    'R' => 'Roboto-Regular.ttf',
                    'I' => 'Roboto-Italic.ttf',
                ],
            ],
        ]);

        $folder = 'generated_pdfs/';
        $html = file_get_contents(URLROOT. '/make_test_pdf/'.$test_id . '/' .$stu_id);
        if(!file_exists($folder)) {
            mkdir($folder, 077, true);
        }
        $mpdf->WriteHTML($html);
        $file_name = $folder.$stu_id.'_test_result_'.date("Y-m-d_H_i_s",time()).'.pdf';
        $mpdf->Output($file_name);


        if(file_exists($file_name)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name)); //Absolute URL
            ob_clean();
            flush();
            readfile($file_name); //Absolute URL
            exit();
        }
        
    }
}