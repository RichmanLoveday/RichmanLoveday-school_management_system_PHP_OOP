<?php //show($submited_test);  
$submited = false;
if(is_object($submited_test) && isset($submited_test) && $submited_test->submitted == 1) {
    $submited = true;
}
?>
<?php if(is_array($saved_answers) && isset($saved_answers)): ?>
    <?php //$percentage = get_answered_percentage($total_questions, $saved_answers); ?>
    <?php $percentage = get_answered_percentage($row->test_id, Auth::getUser_id()); ?>
    <div class="container-fluid text-center">
        <div class="<?= $percentage == 100 ? 'text-success' : 'text-danger' ?>"><?=$percentage?>% Answered</div>
        <div class="mt-2 mb-2 <?= $percentage == 100 ? 'bg-success' : 'bg-primary' ?>" style="height: 5px; width: <?=$percentage?>%">
        </div>
        <?php if(!empty($submited_test)): ?>
            <div class="mb-4">
                <?php if($submited_test->submitted): ?>
                    <small class="text-success text-center">This test has been submited</small>
                <?php else: ?>
                    <small class="text-danger text-center mb-3">This test is yet to be submited</small> <br>
                    <a href="<?= URLROOT ?>/take_test/<?= $row->test_id ?>?submit=true">
                        <button type="button" class="btn btn-danger btn-sm float-end" onclick="submit_test(event)">Submit Test</button>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>


<?php //show($data); 
if(isset($questions) && is_array($questions) && !empty($questions)): ?>
    <div class="d-flex justify-content-between mb-2">
        <center>
            Test Questions
            <p><b>Total Questions: </b><?= count($total_questions) ?></p>
        </center> 
    </div>
    <hr>
    <form method="post">
        <?php $num = $pager->offset; ?>
        <?php foreach($questions as $question): $num++; ?>
            <?php $my_answer = get_answer($question->id, $saved_answers);  // this function compares between saved       answers and question id from the database ?>
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
                                            <?php else: ?>
                                                <input type="radio" style="transform: scale(1.4);" <?= $my_answer == $letter ? 'checked' : '' ?> class="float-end" name="<?= $question->id ?>" value="<?= $letter ?>" id="">
                                            <?php endif; ?>
                                        </li>
                                    </label>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if($question->question_type != 'multiple'): ?>
                            <?php if(!$submited): ?>
                                <input type="text" name="<?= $question->id ?>" value="<?= $my_answer ?>" class="form-control" placeholder="Type your answer here" id="">
                            <?php else: ?>
                               <div><?= $my_answer ?></div>
                            <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <center>
            <?php if(!$submited): ?>
                <small>Click save answers before moving to another page</small> <br> 
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
    function submit_test(e) {
        if(!confirm('Are you sure you want to sumit this test')) {
            e.preventDefault();
            return;
        }

        if(percent < 100) {
            if(!confirm(`You have only answered ${percent} of the test. Are you still sure you want to submit`)) {
            e.preventDefault();
            return;
        }
        }
        
    }
</script>