<div class="card-group justify-content-center mx-auto w-auto">
    <form action="" method="post">
        <h3>Add Test</h3>
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
                
        <input class="form-control" type="text" name="test" value="<?= get_var('test') ?>" placeholder="Test Title"> <br>
        <textarea class="form-control" name="description" id="" cols="30" rows="5" placeholder="Add description to your test" value="<?= get_var('description') ?>"></textarea>
            <a href="<?= URLROOT ?>/single_class/<?= $row->class_id ?>?tab=tests" class="me-3">
                <input type="button" class="btn mt-3 btn-danger btn-sm" value="Cancel">
            </a>
        <input type="submit" class="btn float-end btn-primary btn-sm mt-3" value="Create">
    </form>
</div>