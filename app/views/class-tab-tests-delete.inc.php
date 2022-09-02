<div class="card-group justify-content-center mx-auto w-auto">
    <?php //show($data);  
    if(isset($test_row) && is_object($test_row)): ?>
    <form action="" method="post">
        <?php 
               // print_r($data);     
            if(isset($data['errors'])) : ?>
                <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                    <br><center><h5><?= $errors ?></h5></center>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
        <?php endif; ?>
                
        <label for="">Test Name</label>
        <input readonly class="form-control" type="text" name="test" value="<?= get_var('test', $test_row->test) ?>" placeholder="Test Title"> <br>

        <label for="">Test Description</label>
        <textarea readonly class="form-control" name="description" id="" cols="30" rows="5" placeholder="Add description to your test"><?= get_var('description', $test_row->description) ?></textarea>

        <a href="<?= URLROOT ?>/single_class/<?= $row->class_id ?>?tab=tests" class="me-3">
            <input type="button" class="btn mt-3 btn-success btn-sm" value="Back">
        </a>
        <input type="submit" class="btn float-end btn-danger btn-sm mt-3" value="Delete">
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