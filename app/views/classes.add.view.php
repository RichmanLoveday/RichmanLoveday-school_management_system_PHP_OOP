<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav_bar');
?>
        <div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px;">
        <?php $this->view("includes/crumps", $data); ?>
            <div class="card-group justify-content-center mx-auto w-auto">
                <form action="" method="post">
                    <h3>Add New Class</h3>

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
                
                    <input class="form-control" type="text" name="class" value="<?= get_var('class') ?>" placeholder="Class Name">
                    <a href="<?= URLROOT ?>/classes" >
                        <input type="button" class="btn mt-3 btn-danger btn-sm" value="Cancel">
                    </a>
                    <input type="submit" class="btn float-end btn-primary btn-sm mt-3" value="Create">
                    
                   
                </form>
            
            </div>
        </div>

        <?php

        ?>
<?php $this->view('includes/footer'); ?>
   