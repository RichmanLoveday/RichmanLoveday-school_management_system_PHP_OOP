<?php 
    $type = 'Subjective Question';
    if(isset($_GET['type']) && $_GET['type'] == 'objective') {
        $type = 'Objective Question';
    } elseif(isset($_GET['type']) && $_GET['type'] == 'multipe') {
        $type = 'Multipe Question';
    } else {
        $type = 'Subjective Question';
    }
?>
<center>Add <?= $type ?></center> 
<form action="" class="form w-100" method="post" enctype="multipart/form-data">
    <?php if(isset($data['errors'])) {
    if(count($data['errors']) > 0): ?> 
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Error:</strong> 
            <?php foreach($data['errors'] as $error): ?>
                <br><?= $error ?>
            <?php endforeach; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php else:
        echo ''; ?>
    <?php endif;  } ?>
    <label for="">Question: </label>
    <div class="form-group mb-3">
        <textarea class="from-control w-100" name="question" placeholder="Type your question here.." id="" cols="30" rows="5"><?= get_var('question') ?></textarea>
    </div>
    <!-- <div class="form-group mb-3 mt-3">
        <input type="text" name="comment" placeholder="Leave a comment (optional)" class="form-control" id="">
    </div> -->
    <div class="input-group mb-3">
        <span class="input-group-text" style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;">Comment (optional)</span>
        <input type="text" name="comment" placeholder="Leave a comment" value="<?= get_var('comment') ?>" class="form-control" id="">
    </div>
    <div class="input-group mb-3">
        <span class="input-group-text" style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;"><i class="fa fa-image me-2"></i> Image (optional)</span>
        <input type="file" name="image" class="form-control" value="<?= get_var('image') ?>" id="inputGroupFile01">
        <label class="input-group-text" for="inputGroupFile01" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;">Choose file</label>
    </div>

    <?php if(isset($_GET['type']) && $_GET['type'] == 'objective'): ?>
    <div class="input-group mb-3">
        <span class="input-group-text" style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;">Answer</span>
        <input type="text" name="correct_answer" value="<?= get_var('correct_answer') ?>" placeholder="Enter correct answer here" class="form-control" id="inputGroupFile011">
    </div>
    <?php endif; ?>

    <?php if(isset($_GET['type']) && $_GET['type'] == 'multiple'): ?>
    <div class="input-group mb-3">
        <div class="card" style="width: 100%;">
            <div class="card-header bg-secondary text-white">
                Multiple Choice Answers <button type="button" class="btn btn-sm btn-warning text-white float-end" onclick="add_choice()"><i class="fa fa-plus me-1" ></i>Add choice</button>
            </div>
            <ul class="list-group list-group-flush choice-list">
                <?php if(isset($_POST['choice0'])): 

                    // checking for multiple choice answers
                    $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'I', 'J'];
                    $num = 0;
                    foreach($_POST as $key => $value) {
                        if(strstr($key, 'choice')) { ?>
                            <li class="list-group-item">
                                <?= $letters[$num]?>: 
                                <input type="text" name="<?= $key ?>" value="<?= $value ?>" id="" class="w-100 form-control" placeholder="Type your answer here..">
                                <label style="cursor: pointer;"><input type="radio" <?= $_POST['correct_answer'] == $letters[$num] ? 'checked' : '' ?> value="<?= $letters[$num] ?>" name="correct_answer" id=""> Correct_answer</label>
                            </li>     
                    <?php  $num++;
                        }
                     } ?>

                 <?php else: ?>
                    <li class="list-group-item">
                        A: 
                        <input type="text" name="choice0" id="" class="w-100 form-control" placeholder="Type your answer here..">
                        <label style="cursor: pointer;"><input type="radio" value="A" name="correct_answer" id=""> Correct_answer</label>
                    </li>
                    <li class="list-group-item">
                        B: 
                        <input type="text" name="choice1" id="" class="w-100 form-control" placeholder="Type your answer here..">
                        <label style="cursor: pointer;"><input type="radio" value="B" name="correct_answer" id=""> Correct_answer</label>
                    </li>
                <?php endif; ?>
                
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <a href="<?= URLROOT ?>/single_test/<?= $row->test_id ?>" class="me-3">
        <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-arrow-left me-2"></i>Back</button>
    </a>
    <button class="btn btn-sm btn-danger float-end" type="submit">Save Question</button>
</form>

<script>
    let letters = ['A', 'B', 'C', 'D', 'E', 'F', 'I', 'J'];
    function add_choice() {
        let choices = document.querySelector(".choice-list");
        if(choices.children.length < letters.length) {
            choices.innerHTML += `<li class="list-group-item">
                    ${letters[choices.children.length]}: 
                    <input type="text" name="choice${choices.children.length}" id="" class="w-100 form-control" placeholder="Type your answer here..">
                    <label style="cursor: pointer;"><input type="radio" vlaue="${letters[choices.children.length]}" name="correct_answer" id=""> Correct_answer</label>
                </li>`;
        } 
    }
</script>