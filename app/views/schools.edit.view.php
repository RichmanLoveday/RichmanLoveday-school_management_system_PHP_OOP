<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav_bar');
?>
        <div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px;">
        <?php $this->view("includes/crumps", $data); ?>
            <?php if($school): ?> 
            <div class="card-group justify-content-center mx-auto w-auto">
                <?php 
                // echo '<pre>';
                // print_r($data);
                // echo '</pre>';
            ?>

                
                <form action="" method="post">
                    <h3>Edit School</h3>

                <?php 
                if(isset($data['errors'])) {
            
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
                
                    <input class="form-control" type="text" name="school" value="<?= get_var('school', $school->school) ?>" placeholder="School name">
                    <a href="<?= URLROOT ?>/schools" >
                        <button type="button" class="btn btn-sm btn-danger mt-3"><i class="fa fa-arrow-left me-2"></i>Back</button>
                    </a>
                    <input type="submit" class="btn float-end btn-primary btn-sm mt-3" value="Update">
                   
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
   