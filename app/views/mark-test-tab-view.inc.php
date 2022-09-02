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
    <div class="container-fluid text-center">
        <div class="<?= $percentage == 100 ? 'text-success' : 'text-danger' ?>"><?=$percentage?>% Answered</div>
        <div class="mt-2 mb-2 <?= $percentage == 100 ? 'bg-success' : 'bg-primary' ?>" style="height: 5px; width: <?=$percentage?>%">
        </div>
      
        <div class="<?= $percentage_marked == 100 ? 'text-success' : 'text-danger' ?>"><?=$percentage_marked?>% Marked</div>
        <div class="mt-2 mb-2 <?= $percentage_marked == 100 ? 'bg-success' : 'bg-primary' ?>" style="height: 5px; width: <?=$percentage_marked?>%">
        </div>
        <?php if(!empty($submited_test)): ?>
            <div class="mb-4">
                <?php if($submited_test->submitted && !$marked): ?>
                    <small class="text-success mb-3 text-center">This test has been submited</small> <br>
                    <a href="<?= URLROOT ?>/mark_test/<?= $row->test_id ?>/<?= $student_row->user_id ?>?unsubmit=true">
                        <button type="button" class="btn btn-danger mx-1 btn-sm float-end" onclick="unsubmit_test(event)">Unsubmit Test</button>
                    </a>
                    <a href="<?= URLROOT ?>/mark_test/<?= $row->test_id ?>/<?= $student_row->user_id ?>?set_marked=true">
                        <button type="button" class="btn btn-secondary mx-1 btn-sm float-end" onclick="set_test_as_marked(event)">Set Test As Marked</button>
                    </a>
                    <a href="<?= URLROOT ?>/mark_test/<?= $row->test_id ?>/<?= $student_row->user_id ?>?auto_mark=true">
                        <button type="button" class="btn btn-warning mx-1 text-white btn-sm float-end" onclick="auto_mark(event)">Auto Mark</button>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>


<?php //show($data); 
if(isset($questions) && is_array($questions) && !empty($questions)): ?>
<?php if($marked): ?>
    <?php $score_percent = get_score_percentage($row->test_id, $student_row->user_id); ?>
    <center>
    <small style="font-size: 15px;">Test Score: </small><br><span style="font-size: 35px;"><?= $score_percent ?>%</span>
    </center>
<?php endif; ?>
    <div class="d-flex justify-content-between mb-2 mt-5">
        <center>
            Test Questions
            <p><b>Total Questions: </b><?= count($total_questions) ?></p>
        </center> 
    </div>
    <hr>
    <form method="post">
        <?php $num = $pager->offset; ?>
        <?php foreach($questions as $question): $num++; ?>
            <?php $my_answer = get_answer($question->id, $saved_answers);  // this function compares between saved answers and question id from the database ?>
            <?php $my_mark = get_marked_answer($question->id, $saved_answers);  // this function compares between saved answers and question id from the database ?>
            <div class="card mb-3">
                <div class="card-header">
                    <span class="badge text-white bg-primary rounded">Question #<?= $num;  ?></span> <span style="opacity: 0.7;" class="float-end"> <?= date('F jS, Y H:i:s a', strtotime($question->date)); ?></span>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?=esc($question->question) ?></h5>
                    <?php if(file_exists($question->image)): ?>
                        <img style="width: 250px; height: 150px;" src="<?= URLROOT . '/' . $question->image  ?>" class="d-block mx-auto m-4" alt="no image">
                    <?php endif; ?>
                        <p class="card-text"><?= $question->comment?></p>
                    <?php
                    $type = '';
                    if($question->question_type == 'objective'): 
                        $type = '?type=objective';
                    ?>
                    <?php endif ?>

                    <?php if($question->question_type == 'multiple'): 
                        $type = '?type=multiple'; ?>
                        <div class="card mb-3" style="width: 100%;">
                            <div class="card-header">
                                Select Your answer
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php $choices = json_decode($question->choices); //show($choices); die; ?>
                            
                                <?php foreach($choices as $letter => $value): ?>
                                    <label style="cursor: pointer;">
                                        <li class="list-group-item"><?= $letter ?>: <?= $value ?>
                                            <?php if($submited): ?>
                                                <?php if($my_answer == $letter): ?>
                                                    <i class="fa fa-check float-end"></i>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </li>
                                    </label>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <hr>
                        <center>Teacher's Mark:</center>
                        <?php if(!$marked): ?>
                            <div class="d-flex justify-content-center">
                                <div class="form check p-2">
                                    <input <?= ($my_mark == 1) ? 'checked' : '' ?> type="radio" class="form-check-input" name="<?=$question->id?>" value="1" id="correct<?=$num?>">
                                    <label for="correct<?=$num?>" class="form-check-label">
                                        Corrrect
                                    </label>
                                </div>
                                <div class="form check p-2">
                                    <input <?= ($my_mark == 2) ? 'checked' : '' ?> type="radio" class="form-check-input" name="<?=$question->id?>" value="2" id="wrong<?=$num?>">
                                    <label for="wrong<?=$num?>" class="form-check-label">
                                        Wrong
                                    </label>    
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="font-size: 30px;">
                                <center>
                                    <i class="fa fa-check"></i>
                                    <i class="fa fa-times"></i>
                                </center>
                            </div>
                            
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($question->question_type != 'multiple'): ?>
                        <?php if($submited): ?>
                            <div>Answer: <?= $my_answer ?></div>
                            <hr>
                            <center>Teacher's Mark:</center>
                            <?php if(!$marked): ?>
                            <div class="d-flex justify-content-center">
                                <div class="form check p-2">
                                    <input <?= ($my_mark == 1) ? 'checked' : '' ?> type="radio" class="form-check-input" name="<?=$question->id?>" value="1" id="correct<?=$num?>">
                                    <label for="correct<?=$num?>" class="form-check-label">
                                        Corrrect
                                    </label>
                                </div>
                                <div class="form check p-2">
                                    <input <?= ($my_mark == 2) ? 'checked' : '' ?> type="radio" class="form-check-input" name="<?=$question->id?>" value="2" id="wrong<?=$num?>">
                                    <label for="wrong<?=$num?>" class="form-check-label">
                                        Wrong
                                    </label>    
                                </div>
                            </div>
                            <?php else: ?>
                                <div style="font-size: 30px;">
                                    <?= ($my_mark == 1) ? '<i class="fa text-success fa-check float-end"></i>' : '<i class="fa text-danger fa-times float-end"></i>' ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <center>
            <?php if(!$marked): ?>
                <small>Click save marks before moving to another page</small> <br> 
                <button class="btn btn-primary btn-sm mb-2 mt-3">Save Answers</button>
            <?php else: ?>
                <div></div>
            <?php endif; ?>
        </center>
    </form>
    <?php $pager->display(); ?>
<?php endif; ?>


<script>
    let percent = '<?=$percentage?>';
    let name = '<?= ucwords($student_row->firstname) . ' '. ucwords($student_row->lastname) ?>'
    function unsubmit_test(e) {
        if(!confirm(`Are you sure you want to give ${name} a chance to re-do test`)) {
            e.preventDefault();
            return;
        }
    }

    function auto_mark(e) {
        if(!confirm(`This action may overwrite any custom marks you have saved. Are you sure you want to auto mark`)) {
            e.preventDefault();
            return;
        }
    }

    function set_test_as_marked(e) {
        let percent = <?= $percentage_marked; ?>

        if(percent < 100) {
            e.preventDefault();
            alert(`You have only marked ${percent}% of the questions. You can only set a test as marked after marking all questions.`)
            return;
        }

        if(!confirm(`You won't be able to mark question after this action, continue`)) {
            e.preventDefault();
            return;
        }
    }
</script>