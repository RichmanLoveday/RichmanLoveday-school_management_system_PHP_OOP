<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav_bar'); ?>
<style>
        h1 {
            color: limegreen;
            font-size: 80px;
        }
        a {
            text-decoration: none;
        }
        .card-header {
                font-weight: bold;
        }

        .card {
           min-width: 250px;
        }
        <?php //show($rows); die; ?>
</style>
<div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px;">
        <div class="row d-flex justify-content-center ">
                <?php if(Auth::access('super_admin')): ?>
                        <div class="card col-3 shadow rounded m-4 p-0">
                                <a href="<?= URLROOT ?>/schools">
                                <div class="card-header">SCHOOLS</div>
                                        <h1 class="text-center" style="font-size: 80px;">
                                                <i class="fa fa-graduation-cap"></i>
                                        </h1>
                                <div class="card-footer">View all schools</div>
                                </a>
                        </div>
                <?php endif; ?>
                
                <?php if(Auth::access('admin')): ?>
                        <div class="card col-3 shadow rounded m-4 p-0">
                        <a href="<?= URLROOT ?>/users">
                                <div class="card-header">STAFFS</div>
                                        <h1 class="text-center" style="font-size: 80px;">
                                        <i class="fa fa-chalkboard-teacher"></i>
                                        </h1>
                                <div class="card-footer">View all staff members</div>
                        </a>
                        </div> 
                <?php endif; ?>
                
                <?php if(Auth::access('reception')): ?>
                        <div class="card col-3 shadow rounded m-4 p-0">
                        <a href="<?= URLROOT ?>/students">
                                <div class="card-header">STUDENTS</div>
                                        <h1 class="text-center" style="font-size: 80px;">
                                                <i class="fa fa-users"></i>
                                        </h1>
                                <div class="card-footer">View all students</div>
                        </a>
                        </div>  
                <?php endif; ?>
                
                <?php if(Auth::access('lecturer') || Auth::getRank() == 'student'): ?>
                        <div class="card col-3 shadow rounded m-4 p-0">
                        <a href="<?= URLROOT ?>/classes">
                                <div class="card-header">CLASSES</div>
                                        <h1 class="text-center" style="font-size: 80px;">
                                                <i class="fa fa-university"></i>
                                        </h1>
                                <div class="card-footer">View all classes</div>
                        </a>
                        </div>  
                <?php endif; ?>
              
               
                <?php if(Auth::access('lecturer') || Auth::getRank() == 'student'): ?>
                        <div class="card col-3 shadow rounded m-4 p-0">
                        <a href="<?= URLROOT ?>/tests">
                                <div class="card-header">TEST</div>
                                        <h1 class="text-center" style="font-size: 80px;">
                                                <i class="fa fa-book"></i>
                                        </h1>
                                <div class="card-footer">View all test</div>
                        </a>
                        </div>
                <?php endif; ?> 

                <?php if(Auth::access('admin')): ?>
                        <div class="card col-3 shadow rounded m-4 p-0">
                        <a href="<?= URLROOT ?>/statistics">
                                <div class="card-header">STATISTICS</div>
                                        <h1 class="text-center" style="font-size: 80px;">
                                                <i class="fa fa-calendar-check-o"></i>
                                        </h1>
                                <div class="card-footer">View student statustics</div>
                        </a>
                        </div> 
                <?php endif; ?> 

                <?php if(Auth::access('admin')): ?>
                        <div class="card col-3 shadow rounded m-4 p-0">
                        <a href="<?= URLROOT ?>/settings">
                                <div class="card-header">SETTINGS</div>
                                        <h1 class="text-center" style="font-size: 80px;">
                                                <i class="fa fa-cogs"></i>
                                        </h1>
                                <div class="card-footer">View app settings</div>
                        </a>
                        </div> 
                <?php endif; ?>
                
                <div class="card col-3 shadow rounded m-4 p-0">
                <a href="<?= URLROOT ?>/profile">
                        <div class="card-header">PROFILE</div>
                                <h1 class="text-center" style="font-size: 80px;">
                                        <i class="fa fa-user"></i>
                                </h1>
                        <div class="card-footer">View your profile</div>
                </a>
                </div> 
                
                <div class="card col-3 shadow rounded m-4 p-0">
                <a href="<?= URLROOT ?>/logout">
                        <div class="card-header">LOGOUT</div>
                                <h1 class="text-center" style="font-size: 80px;">
                                        <i class="fa fa-sign-out"></i>
                                </h1>
                        <div class="card-footer">Logout from the system</div>
                </a>
                </div>    
        </div>
</div>
<?php $this->view('includes/footer'); ?>
   