<form action="" method="post" class="form mx-auto mt-3" style="width: 100%;max-width: 400px;">
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

    <h4>Remove Student</h4>
        <input autofocus class="form-control" type="text" value="<?= get_var('name') ?>" name="name" placeholder="Student Name"> <br>
            <a href="<?= URLROOT ?>/single_class/<?= $row->class_id ?>?tab=students">
                <button type="button" class="btn btn-sm btn-danger float-start"><i class="fa fa-arrow-left me-2"></i>Back</button>
            </a>
        <input type="submit" value="Search" name="search" class="btn btn-primary btn-sm float-end">
    <div class="clearfix"></div>
</form>


<div class="container-fluid">
    <?php //show($data);?>            <?php //show($_POST) ?>
    <?php if(isset($results) && $results == true): ?>
        <form action="" method="post">
            <div class="card-group mt-3 justify-content-center">
                <?php foreach($results as $row): ?>
                        <?php include(view_path('includes/user'))  ?>
                <?php endforeach; ?>
            </div>
        </form>
    <?php else: ?>
            <?php if($_SERVER['REQUEST_METHOD'] == 'POST' && count($errors) < 1): ?>
            <hr>
            <center><h4>No results were found</h4></center>
            <?php endif; ?>
    <?php endif; ?>
</div>