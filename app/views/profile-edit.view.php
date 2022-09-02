<?php $this->view("includes/header"); ?>
<?php $this->view("includes/nav_bar"); ?>

<div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px">
<?php $this->view("includes/crumps", $data); ?>
<h4 class="text-center">Edit Profile</h4>
<?php if($rows): //show($data) ?>
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <?php           
                $image = get_image($rows->image, $rows->gender);              
            ?>
            
                <img class="d-block mx-auto w-50 border" src="<?= $image ?>" alt="no_image">
                <?php if(Auth::access('reception') || Auth::i_own_content($rows)): ?>
            <form action="" method="post" class="p-4" enctype="multipart/form-data">
                        <div class="d-flex justify-content-around">
                            <div style="width: 70%;">
                                <label for="image_browser" class="btn btn-sm btn-info text-white" style="width: 100%;">
                                    <input type="file" name="image" id="image_browser" onchange="display_image_name(this.files[0].name)" style="display: none;">
                                    Browse Image
                                </label>
                            </div>
                        </div>
                        <center><small class="text_muted file_info"></small></center> 
                    <?php endif; ?>
                    
                </div>
                <div class="col-sm-8 col-md-8 bg-light p-2">
                    <div class="mx-auto rounded">
                
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
                            <input class="form-control my-3" type="text" name="firstName" placeholder="First Name" value="<?= get_var('firstName', $rows->firstname) ?>">
                        </div>
            
                        <div class="form-group my-3">
                            <input class="form-control" type="text" name="lastName" placeholder="Last Name" value="<?= get_var('lastName', $rows->lastname) ?>">
                        </div>

                        <div class="form-group my-3">
                            <input class="form-control" type="email" name="email" placeholder="Email" value="<?= get_var('email', $rows->email) ?>">
                        </div>

                        <div class="form-group my-3">
                            <select class="form-control my-3" name="gender" id="">
                                <option <?= get_select('gender', $rows->gender) ?> value="<?= $rows->gender ?>"><?= $rows->gender ?></option>
                                <option <?= get_select('gender', 'male') ?> value="male">Male</option>
                                <option <?= get_select('gender', 'female') ?> value="female">Female</option>
                            </select>
                        </div>
                        

                        <div class="form-group my-3">
                            <select class="form-control my-3" name="rank" id="">
                                <option <?= get_select('rank', $rows->rank) ?> value="<?= $rows->rank ?>"><?= $rows->rank ?></option>
                                <?php if(Auth::access('admin')): ?>
                                    <option <?= get_select('rank', 'reception') ?> value="reception">Reception</option>
                                    <option <?= get_select('rank', 'lecturer') ?> value="lecturer">Lecturer</option>
                                    <option <?= get_select('rank', 'admin') ?> value="admin">Admin</option>
                                <?php endif; ?>
                                <?php 
                                    if(Auth::getRank() == 'super_admin'): ?>
                                    <option <?= get_select('rank', 'super_admin') ?> value="super_admin">Super Admin</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group my-3">
                            <input class="form-control" type="password" name="password" placeholder="Password" value="<?= get_var('password') ?>">
                        </div>

                        <div class="form-group my-3">
                            <input class="form-control" type="password" name="confirm_password" placeholder="Re-Type Password" value="<?= get_var('confirm_password') ?>">
                        </div>
                        <br>
                        <input type="submit" value="Save Changes" class="btn btn-primary text-white btn-sm float-end">

                        <a href="<?= URLROOT ?>/profile/<?= $rows->user_id ?>">
                            <button type="button" class="btn btn-success text-white btn-sm"><i class="fa fa-arrow-left me-2"></i>Back to profile</button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
   
    <?php else: ?>
        <h3 class="text-center">That Profile was Not Found</h3>
    <?php endif; ?>
</div>

<script>

    function display_image_name(file_name) {
        document.querySelector('.file_info').innerHTML = '<b>Selected file:</b><br>' + file_name;
    }

</script>

<?php $this->view("includes/footer"); ?>