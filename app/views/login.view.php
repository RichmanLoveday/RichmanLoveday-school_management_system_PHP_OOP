<?php
// echo '<pre>';
// print_r($data);
// echo '</pre>';
?>
<?php $this->view('includes/header'); ?>
        <div class="container-fluid">
           <div class="mx-auto shadow rounded" style="width:100%; margin-top: 150px; max-width: 400px;">
            <form method="post" class="p-4">
                <h2 class="text-center">My School</h2>
                <img src="<?= URLROOT ?>/assets/logo.png" class="d-block mx-auto rounded-circle mt-3" alt="" style="width:50px; height: 50px;">
                <h3>Login</h3>

                
                <?php if(count($data['errors']) > 0): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> 
                    <?php foreach($data['errors'] as $error): ?>
                        <br><?= $error ?>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php else:
                    echo ''; ?>
                <?php endif ?>

                <div class="form-group">
                    <input class="form-control" type="email" name="email" placeholder="Email" value="<?= get_var('email') ?>">
                </div>
                <br>
                <div class="form-group mb-2">
                    <input class="form-control" type="password" name="password" placeholder="Password" value="<?= get_var('password')?>">
                </div>
                <br>
                <input type="submit" value="Login" class="btn btn-primary btn-sm">
            </form>
           </div>
        </div>
<?php $this->view('includes/footer'); ?>
   