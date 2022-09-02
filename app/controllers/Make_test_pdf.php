<?php
/*
** make pdf Controller
*
*/

use app\core\Controller;

class Make_test_pdf extends Controller {

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
        // $this->lectClassM = new class_lecturer();
        $this->classModel = new ClassM();
        $this->userModel = new User();
        // $this->studentClassM = new Class_student();
        $this->testModel = new TestM();
        $this->questionModel = new QuestionsM();
        $this->answersM = new AnswersM();
    }


    public function index($test_id = '', $stu_id = '') {
    
        // for pagination
        $limit = 3000;
        $pager = new Pager($limit);
        $offset = $pager->offset;

        $this->result = $this->testModel->where('test_id', $test_id);            // searching for test by ID
        $questions = $this->questionModel->where('test_id', $test_id, 'asc', $limit, $offset);          //searching and limiting questions
        $this->all_quest = $this->questionModel->get_all_quest($test_id);          //searching for all question
        $total_questions = is_array($this->all_quest) ? $this->all_quest : 0;
    
        $this->crumbs = [
            ['Dasboard', ''],
            ['Test', 'tests'],
        ];

        if(is_array($this->result) && $this->result) {
            $this->profile = 'single_test/' . $this->result[0]->test_id;
            $this->crumbs[] = [$this->result[0]->test, $this->profile];
            //var_dump($this->result); die;
            $result = $this->result[0];
            $edit = 1;

            if(!$result->status) {
                $this->testModel->single_update($result->test_id, 'editable', $edit);
            }
        }
        
        $arr1 = [];
        $arr1['user_id'] = $stu_id;
        $arr1['test_id'] = $test_id;
        $submited_test = $this->answersM->row_exist(['test_id' => $test_id, 'user_id' => $stu_id], 'submited_test');
        $submited_test_row = is_array($submited_test) ? $submited_test[0] : '';
        
        // gettting the student information
        if($submited_test_row) {
            $student_row = $this->userModel->row_exist(['user_id' => $stu_id], 'users');
            $student_row = $student_row[0];
        }


        // getting saved questions from the data base   
        $saved_answers = $this->answersM->row_exist(['test_id' => $test_id, 'user_id' => $stu_id]);

        //show($this->result); die;
        //extract($submited_test);
        // extract($result);
        $row = $this->result[0];
        $submited_test = $submited_test[0];
        if($row && $submited_test && $submited_test->submitted && (!$row->status)): ?>

            <style>
                table {
                    width: 100%;
                    border: 1px solid rgba(0, 0, 0, 0.3);
                    border-collapse: collapse;
                    background-color: rgba(205, 209, 228, 0.2);
                    color: rgba(0, 0, 0, 0.9);
                
                }

                td, th {
                    border: 1px solid rgba(0, 0, 0, 0.3);
                    padding: 7px;
                }

                th {
                    background: rgba(0, 0, 0, 0.7);
                    color: white;
                }

                td, a {
                    text-decoration: none;
                    font-size: 15px;
                }
            </style>
            <div style="font-family: tahoma; max-width: 1000px; margin: 20px auto;">
                <table> 
                    <tr>
                        <td style="text-align: center; font-size: 20px; font-weight: bold;" colspan="4">Test: <?= esc(ucwords($row->test)) ?></td>
                    </tr>
                    <tr>
                        <th>
                            <span style="font-size: 15px;">Class Name:  </span>
                            <td>
                                <?= ucwords($row->class->class) ?>
                            </td>
                        </th>
                        <th>
                            <span style="font-size: 15px;">Student Name:   </span>
                            <td> 
                                <?= ucwords($student_row->firstname) . ' '. ucwords($student_row->lastname) ?>
                            </td>
                        </th>
                        
                    </tr>
                    <tr>
                        <th><span style="font-size: 15px;">Created By: </span>
                            <td>
                                <?= esc($row->user->firstname) ?> <?= esc($row->user->lastname) ?>
                            </td>
                        </th>
                        <th><span style="font-size: 15px;">Date Created:  </span><td><?= get_date($row->date) ?></td></th>
                    </tr>
                        <?php $active = $row->status ? 'No' : 'Yes'; ?>
                    <tr style="text-align: center;">
                        <td colspan="4"><b>Test Description:</b> <br><?= esc($row->description) ?></td>
                    </tr>
                </table>

                <br>


                <?php //show($data);  
                    $submited = false;
                    if(is_object($submited_test) && isset($submited_test) && $submited_test->submitted == 1) {
                        $submited = true;
                    }

                    $marked = false;
                    if(is_object($submited_test) && isset($submited_test) && ($submited_test->marked == 1 || $submited_test->marked == 2)) {
                        $marked = true;
                    }
                ?>
                <?php if(is_array($saved_answers) && isset($saved_answers)): ?>
                    <?php //$percentage = get_answered_percentage($total_questions, $saved_answers); ?>
                    <?php $percentage = get_answered_percentage($row->test_id, $student_row->user_id); ?>
                    <?php $percentage_marked = get_marked_percentage($row->test_id, $student_row->user_id); ?>
                    
                    <div style="display: flex; justify-content: end;">
                        <span><?=$percentage?>% Answered | <?=$percentage_marked?>% Marked</span>
                        
                    </div>
                    
                <?php endif; ?>


                <?php //show($data); 
                if(isset($questions) && is_array($questions) && !empty($questions)): ?>
                    <?php if($marked): ?>
                        <?php $score_percent = get_score_percentage($row->test_id, $student_row->user_id); ?>
                    <?php endif; ?>
                    <center>
                    <small style="font-size: 15px;">Test Score: </small><br><span style="font-size: 35px;"><?= $score_percent ?>%</span>
                    </center>
                    <div class="d-flex justify-content-between mb-2 mt-5">
                        <center>
                            <p><b>Total Questions: </b><?= count($total_questions) ?></p>
                        </center> 
                    </div>
                    <hr>
                    <?php $num = $pager->offset; ?>
                    <?php foreach($questions as $question): $num++; ?>
                        <?php $my_answer = get_answer($question->id, $saved_answers);  // this function compares between saved       answers and question id from the database ?>
                        <?php $my_mark = get_marked_answer($question->id, $saved_answers);  // this function compares between saved answers and question id from the database ?>
                        <div class="card mb-3" style="border-bottom: solid thin #555; padding: 10px">
                            <div class="card-header">
                                
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span class="badge text-white bg-primary rounded">Question #<?= $num;  ?></span> <span style="opacity: 0.7;" class="float-end"> <?= date('F jS, Y H:i:s a', strtotime($question->date)); ?></span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?=esc($question->question) ?></h5>
                                <?php if(file_exists($question->image)): ?>
                                    <center><img style="width: 250px; height: 150px;" src="<?= URLROOT . '/' . $question->image  ?>" class="d-block mx-auto m-4" alt="no image"></center>
                                <?php endif; ?>
                                    <p class="card-text"><?= $question->comment?></p>
                                <?php
                                $type = '';
                                if($question->question_type == 'objective'): 
                                    $type = '?type=objective';
                                ?>
                                <?php endif ?>
                                <hr>
                                <div style="background-color: rgba(0, 0, 0, 0.2);padding:10px;">
                                    <?php if($question->question_type == 'multiple'): 
                                        $type = '?type=multiple'; ?>
                                        <div class="card mb-3" style="width: 100%;">
                                            <?php $choices = json_decode($question->choices); //show($choices); die; ?>
                                            <?php $student_ans = '' ?>
                                            <?php foreach($choices as $letter => $value): ?>
                                                <label style="cursor: pointer;">
                                                    <?php if($submited): ?>
                                                        <?php if($my_answer == $letter): 
                                                            $student_ans = $value;
                                                            ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <div style="margin-bottom: 2px"> 
                                            <span style="text-align: center; font-size: 16px; font: bold;"> Student Answer: <?=$student_ans?></span>
                                        </div>
                                        
                                        <div style="display: flex; justify-content: space-between;">
                                            <span style="text-align: center; font-size: 16px; font: bold;">Teacher's Mark:</span>
                                            <div style="font-size: 12px; ">
                                                <?= ($my_mark == 1) ? '<span style=color:green;font-weight:bold;>Correct</span>' : '<span style=color:red;font-weight:bold;>Wrong</span>' ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($question->question_type != 'multiple'): ?>
                                        <?php if($submited): ?>
                                            <div style="margin-bottom: 2px"> 
                                                <span style="text-align: center; font-size: 16px; font: bold;"> Student Answer: <?=$my_answer?></span>       
                                            </div>
                                            <div style="display: flex; justify-content: space-between;">
                                                <span style="text-align: center; font-size: 16px; font: bold;">Teacher's Mark:</span>
                                                <div style="font-size: 12px; ">
                                                    <?= ($my_mark == 1) ? '<span style=color:green;font-weight:bold;>Correct</span>' : '<span style=color:red;font-weight:bold;>Wrong</span>' ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; 
    }
}