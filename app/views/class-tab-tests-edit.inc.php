<div class="card-group justify-content-center mx-auto w-auto">
    <?php //show($data);  
    if(isset($test_row) && is_object($test_row)): ?>
    <form action="" method="post">
        <h3>Edit Test</h3>
        <?php 
               // print_r($data);     
            if(isset($data['errors'])) {
                    //print_r($data);
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
                
        <input class="form-control" type="text" name="test" value="<?= get_var('test', $test_row->test) ?>" placeholder="Test Title"> <br>
        <textarea class="form-control" name="description" id="" cols="30" rows="5" placeholder="Add description to your test"><?= get_var('description', $test_row->description) ?></textarea>

        <?php 
            $status = get_var('status', $test_row->status);
            $active_chk = $status  ? '' : 'checked';
            $disabled_chk = $status ? 'checked' : '';
        ?>

        <input type="radio" name="status" id="" value="0" <?= $active_chk ?>>Active |
        <input type="radio" name="status" id="" value="1" <?= $disabled_chk ?>>Disabled <br><br>
            <a href="<?= URLROOT ?>/single_class/<?= $row->class_id ?>?tab=tests" class="me-3">
                <input type="button" class="btn mt-3 btn-danger btn-sm" value="Back">
            </a>
        <input type="submit" class="btn float-end btn-primary btn-sm mt-3" value="Save">
    </form>
    
    <?php else: ?>
        <div class="mx-auto">
            <center>Sorry, that test was not found! </center> 
            <a class="d-flex justify-content-center mt-3" style="text-decoration: none;" href="<?= URLROOT ?>/single_class/<?= $row->class_id ?>?tab=tests">
                <button type="button" class="btn btn-success text-white btn-sm"><i class="fa fa-arrow-left me-2"></i>Back to tests</button>
            </a>
        </div>
    <?php endif; ?>
</div>