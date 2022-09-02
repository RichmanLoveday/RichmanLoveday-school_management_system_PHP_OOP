<?php $this->view('includes/header'); ?>
<?php
// echo '<pre>'; 
// print_r($data);
// echo '</pre>';
// die(); ?>
        <div class="container-fluid">
           <div class="mx-auto shadow rounded" style="width:100%; margin-top: 15px; max-width: 400px;">
            <form action="" method="post" class="p-4">
                <h2 class="text-center">My School</h2>
                <img src="<?= URLROOT ?>/assets/logo.png" class="d-block mx-auto rounded-circle mt-3" alt="" style="width:50px; height: 50px;">
                <h3>Add User</h3>
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
                
                <div class="form-group">
                    <input class="form-control my-3" type="text" name="firstName" placeholder="First Name" value="<?= get_var('firstName') ?>">
                </div>
    
                <div class="form-group my-3">
                    <input class="form-control" type="text" name="lastName" placeholder="Last Name" value="<?= get_var('lastName') ?>">
                </div>

                <div class="form-group my-3">
                    <input class="form-control" type="email" name="email" placeholder="Email" value="<?= get_var('email') ?>">
                </div>

                <div class="form-group my-3">
                    <select class="form-control my-3" name="gender" id="">
                        <option value="">---Select a Gender----</option>
                        <option <?= get_select('gender', 'male') ?> value="male">Male</option>
                        <option <?= get_select('gender', 'female') ?> value="female">Female</option>
                    </select>
                </div>
                
                <div class="form-group my-3">
                    <?php if($mode == 'students'): ?>
                        <input type="text" class="form-control" placeholder="Students" value="Student" disabled>
                        <input type="hidden" name="rank" value="student">
                    <?php else: ?>
                    <select class="form-control my-3" name="rank" id="">
                        <option value="">---Select a Rank----</option>
                        <option <?= get_select('rank', 'student') ?> value="student">Student</option>
                        <option <?= get_select('rank', 'reception') ?> value="reception">Reception</option>
                        <option <?= get_select('rank', 'lecturer') ?> value="lecturer">Lecturer</option>
                        <option <?= get_select('rank', 'admin') ?> value="admin">Admin</option>
                        <?php 
                            if(Auth::getRank() == 'super_admin'): ?>
                            <option <?= get_select('rank', 'super_admin') ?> value="super_admin">Super Admin</option>
                        <?php endif; ?>
                    </select>
                    <?php endif; ?>
                </div>
                
                
                
                <div class="form-group my-3">
                    <input class="form-control" type="password" name="password" placeholder="Password" value="<?= get_var('password') ?>">
                </div>

                <div class="form-group my-3">
                    <input class="form-control" type="password" name="confirm_password" placeholder="Re-Type Password" value="<?= get_var('confirm_password') ?>">
                </div>
                
                <br>
                <?php if($mode == 'students'): ?>

                    <a href="<?= URLROOT ?>/students" >
                        <input type="button" class="btn btn-danger btn-sm" value="Cancel">
                    </a>

                <?php else: ?>

                    <a href="<?= URLROOT ?>/users" >
                        <input type="button" class="btn btn-danger btn-sm" value="Cancel">
                    </a>

                <?php endif; ?>
                <input type="submit" value="Add User" class="btn btn-primary btn-sm float-end">
            </form>
           </div>
        </div>
<?php $this->view('includes/footer'); ?>
   