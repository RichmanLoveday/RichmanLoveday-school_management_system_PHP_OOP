<hr>
<?php if($row->status): ?>
    <div class="d-flex justify-content-between mb-2">
        <center>
            Test Questions
            <p><b>Total Questions: </b><?= $total_questions ?></p>
        </center> 
        <div class="dropdown dropdown-menu-end">
            <button class="btn btn-danger btn-sm dropdown-toggle dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false" type="button" aria-haspopup="true"><i class="fa fa-bars"></i>
                Add
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= URLROOT ?>/single_test/addquestion/<?= $row->test_id?>?type=objective">
                    Add Objective Question
                </a>
                <a class="dropdown-item" href="<?= URLROOT ?>/single_test/addquestion/<?= $row->test_id?>">
                    Add Subjective Question
                </a> 
                <hr class="dropdown-divider">
                <a class="dropdown-item" href="<?= URLROOT ?>/single_test/addquestion/<?= $row->test_id?>?type=multiple">
                    Add Multiple choice Question 
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php //show($data); die;
//$row = $row[0];
if(isset($questions) && is_array($questions)): ?>
    <?php $num = $total_questions + 1; ?>
    <?php foreach($questions as $question): $num--; ?>
    <div class="card mb-3 shadow">
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
                <p class="card-text"><b>Answer: </b><?= $question->correct_answer?></p>
            <?php endif; ?>

           <?php if($question->question_type == 'multiple'): 
                $type = '?type=multiple';
            ?>
                <div class="card mb-3" style="width: 100%;">
                    <div class="card-header">
                        Multiple choice
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php $choices = json_decode($question->choices); //show($choices); die; ?>
                        <?php foreach($choices as $letter => $value): ?>
                            <li class="list-group-item"><?= $letter ?>: <?= $value ?>
                                <?php if(trim($letter) == trim($question->correct_answer)): ?>
                                    <i class="float-end fa fa-check"></i>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?> 
                    </ul>
                </div>
                <p class="card-text"><b>Answer: </b><?= $question->correct_answer?></p>
            <?php endif; ?>
            <?php if($row->editable): ?>
                <a href="<?= URLROOT ?>/single_test/deletequestion/<?= $row->test_id ?>/<?= $question->id ?><?= $type ?>" class="me-3">
                    <button class="btn btn-sm btn-danger text-white float-end"><i class="fa fa-trash"></i></button>
                </a>

                <a href="<?= URLROOT ?>/single_test/editquestion/<?= $row->test_id ?>/<?= $question->id ?><?= $type ?>" class="me-3">
                    <button class="btn btn-sm btn-info me-2 text-white float-end"><i class="fa fa-edit"></i></button>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>