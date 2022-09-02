<?php 
    $quest_type = 'Delete subjective Question'; 
    if(isset($_GET['type']) && $_GET['type'] == 'objective') {
        $quest_type = 'Delete objective Question';
    } elseif(isset($_GET['type']) && $_GET['type'] == 'multiple') {
        $quest_type = 'Delete multiple Choice';
    }
?>
    <?php //show($data); ?>
<?php if(is_object($question)): ?>
    <center><?= $quest_type ?></center> 
    <?php  ?>

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
        <div class="input-group mb-3">
            <textarea disabled class="from-control w-100" name="question" placeholder="Type your question here.." id="" cols="30" rows="5"><?= get_var('question', $question->question) ?></textarea>
        </div>
        <!-- <div class="form-group mb-3 mt-3">
            <input type="text" name="comment" placeholder="Leave a comment (optional)" class="form-control" id="">
        </div> -->
        <div class="input-group mb-3">
            <span class="input-group-text" style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;">Comment (optional)</span>
            <input readonly type="text" name="comment" placeholder="Leave a comment" value="<?= get_var('comment', $question->comment) ?>" class="form-control" id="">
        </div>

        <?php if(file_exists($question->image)): ?>
        <div class="p-4">
            <img style="max-width: 50%; max-height: 50%;" src="<?= URLROOT . '/' . $question->image ?>" class="d-block mx-auto" alt="no-image" >
        </div>
        <?php endif; ?>

        <?php if(isset($_GET['type']) && $_GET['type'] == 'objective'): ?>
        <div class="input-group mb-3">
            <span class="input-group-text" style="border-top-left-radius: 0px; border-bottom-left-radius: 0px;">Answer</span>
            <input type="text" name="correct_answer" value="<?= get_var('correct_answer', $question->correct_answer) ?>" placeholder="Enter correct answer here" class="form-control" id="inputGroupFile011">
        </div>
        <?php endif; ?>

        <a href="<?= URLROOT ?>/single_test/<?= $row->test_id ?>" class="me-3">
            <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-arrow-left me-2"></i>Back</button>
        </a>
        <button class="btn btn-sm btn-danger float-end" type="submit">Delete</button>
    </form>
    <?php else: ?>
        <center>
            <div class="mb-3">
                <h4>Sorry that question was not found!</h4>
            </div>
            <a href="<?= URLROOT ?>/single_test/<?= $row->test_id ?>" class="me-3">
                <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-arrow-left me-2"></i>Back</button>
            </a>
        </center>
<?php endif; ?>