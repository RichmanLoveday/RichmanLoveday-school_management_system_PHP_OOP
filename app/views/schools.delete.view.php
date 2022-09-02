<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav_bar');
?>
        <div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px;">
        <?php $this->view("includes/crumps", $data); ?>
            <?php if($result): ?> 
            <div class="card-group justify-content-center mx-auto w-auto">
            <?php 
                // echo '<pre>';
                // print_r($data);
                // echo '</pre>';
            ?>

                
                <form action="" method="post">
                    <h3>Are you sure you want to delete school!</h3>
                
                    <input disabled autofocus class="form-control" type="text" name="school" value="<?= get_var('school', $result[0]->school) ?>" placeholder="School name">
                    <input type="hidden" name="id" value="">
                    <a href="<?= URLROOT ?>/schools">
                        <button type="button" class="btn btn-success text-white btn-sm mt-3"><i class="fa fa-arrow-left me-2"></i>Back</button>
                    </a>
                    <input type="submit" class="btn float-end btn-danger text-white btn-sm mt-3" value="Delete">
                   
                </form>
                <?php else : ?>
                    <div class="text-center">
                        <h3 class="text-center mb-4">No School Found At The Time! </h3>
        
                        <a href="<?= URLROOT ?>/schools">
                            <button class="btn btn-danger text-white btn-sm"><i class="fa fa-arrow-left me-2"></i>Back</button>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php

        ?>
<?php $this->view('includes/footer'); ?>
   