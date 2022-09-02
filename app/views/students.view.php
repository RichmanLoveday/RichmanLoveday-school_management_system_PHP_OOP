<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav_bar');
// echo '<pre>';
// print_r($rows);
// die();
?>
        <div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px;">
        <?php $this->view("includes/crumps", $data); ?>
          <h4 class="text-center">Students</h4>
            <nav class="navbar navbar-light bg-light">
                <form class="form-inline ms-3">
                    <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="input-group-text" id="basic-addon1" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;"><i class="fa fa-search "></i>&nbsp;</button>
                    </div>
                    <input type="text" class="form-control" name="find" value="<?= get_Var('find') ?>" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                </form>
                <a href="<?= URLROOT ?>/signup?mode=students" class="me-3">
                    <button class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>Add New</button>
                </a>
            </nav>
               
            <div class="card-group justify-content-center mx-auto w-auto">
                <?php if($rows):

                    foreach($rows as $row):  ?>
                        <?php include(view_path('includes/user'));  ?>
                <?php endforeach; ?>
                <?php else: ?>
                    <h4>No student was found at this time</h4>
                <?php endif; ?>
            </div>

           <?php $pager->display(); ?>
            
        </div>

        <?php

        ?>
<?php $this->view('includes/footer'); ?>
   