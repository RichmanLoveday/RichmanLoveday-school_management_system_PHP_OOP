<?php $this->view("includes/header"); ?>
<?php $this->view("includes/nav_bar"); ?>

<div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px">
<?php $this->view("includes/crumps", $data); ?>
<h4 class="text-center">Profile</h4>
<?php if($rows): //show($rows); die; ?>
    <div class="row">
        <div class="col-sm-4 col-md-3">
        <?php
                           
            $image = get_image($rows->image, $rows->gender);
                               
        ?>
            <img class="rounded-circle d-block mx-auto w-50 border" src="<?= $image ?>" alt="no_image">
            <h4 class="text-center h5"><?= esc($rows->firstname)  . ' '. esc($rows->lastname )?></h4>

            <?php if(Auth::access('admin') || (Auth::access('reception') && $rows->rank == 'student')): ?>
                <div class="d-flex justify-content-around my-4">
                <a href="<?= URLROOT ?>/profile/edit/<?= $rows->user_id ?>" style="width: 35%;">
                    <button class="btn btn-sm btn-success" style="width: 100%;">Edit</button>
                </a>

                <a href="<?= URLROOT ?>/profile/delete/<?= $rows->user_id ?>" style="width: 35%;">
                    <button class="btn btn-sm btn-danger" style="width: 100%;">Delete</button>
                </a>
                </div>
            <?php endif; ?>
            
        </div>
        <div class="col-sm-8 col-md-8 bg-light p-2">
            <table class="table table-hover table-stripped">
                <tr><th>Firstname:<td><?= esc($rows->firstname)?></td></th></tr>
                <tr><th>Lastname: <td><?= esc($rows->lastname) ?></td></th></tr>
                <tr><th>Gender: <td><?= esc($rows->gender)?></td></th></tr>
                <tr><th>Email: <td><?= esc($rows->email)?></td></th></tr>
                <tr><th>Rank: <td><?= esc($rows->rank) ?></td></th></tr>
                <tr><th>Date Created: <td><?= get_date($rows->date) ?></td></th></tr>
            </table>
        </div>
        
    </div>

    <hr>

    <div class="container">
        
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link <?= ($page_tab == 'info') ? 'active' : ''?>" href="<?= URLROOT ?>/profile/<?= $rows->user_id ?>?tab=info">Basic Info</a>
        </li>

        <?php if(Auth::access('lecturer') || Auth::i_own_content($rows)): ?>
            <li class="nav-item">
                <a class="nav-link <?= ($page_tab == 'classes') ? 'active' : ''?>" href="<?= URLROOT ?>/profile/<?= $rows->user_id ?>?tab=classes">My Classes</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= ($page_tab == 'test') ? 'active' : ''?>" href="<?= URLROOT ?>/profile/<?= $rows->user_id ?>?tab=test">Test</a>
            </li>
        <?php endif; ?>
    </ul>

    </div>


    <?php 
        switch($page_tab) {
            case 'info':
                include(view_path('profile-tab-info'));
                break;
            case 'classes':
                if(Auth::access('lecturer') || Auth::i_own_content($rows)) {
                    include(view_path('profile-tab-classes'));
                } else {
                    $this->view('access-denied');
                }
                break;
            case 'test':
                include(view_path('profile-tab-test'));
                break;
            default:
            break;
        }
    ?>

    <?php else: ?>
        <h3 class="text-center">That Profile was Not Found</h3>
    <?php endif; ?>
</div>
<?php $this->view("includes/footer"); ?>