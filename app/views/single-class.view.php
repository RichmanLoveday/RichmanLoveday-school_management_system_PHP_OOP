<?php $this->view("includes/header"); ?>
<?php $this->view("includes/nav_bar"); ?>
<?php //show($data); die; ?>
<div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px">
<?php $this->view("includes/crumps", $data); ?>
<?php if($row):
    $row = $row[0]; ?>

    <h4 class="text-center"><?= ucwords($row->class) ?></h4>
    <div class="row">
        <div class="col-4">
            <h4 class="text-center h5"><?php // esc($row->firstname)  . ' '. esc($row->lastname ) ?></h4>
        </div>
        <table class="table table-hover table-striped table-bordered">
            <tr><th>Class Name:<td><?= esc($row->class)?></td></th>
            <th>Created By: <td><?= esc($row->user->firstname) ?> <?= esc($row->user->lastname) ?></td></th>
            <th>Date Created: <td><?= get_date($row->date) ?></td></th></tr>
        </table>
    </div>

    <hr>
        
    <ul class="nav nav-tabs">
        <?php if(Auth::access('lecturer')): ?>
            <li class="nav-item">
                <a class="nav-link <?= $page_tab == 'lecturers' ? 'active' : '' ?> " href="<?= URLROOT ?>/single_class/<?= $row->class_id ?>?tab=lecturers">Lecturers</a>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link <?= $page_tab == 'students' ? 'active' : '' ?> "  href="<?= URLROOT ?>/single_class/<?= $row->class_id ?>?tab=students">Students</a>
        </li>
        <?php if(Auth::access('lecturer')): ?>
            <li class="nav-item">
                <a class="nav-link <?= $page_tab == 'tests' ? 'active' : '' ?> " href="<?= URLROOT ?>/single_class/<?= $row->class_id ?>?tab=tests">Tests</a>
            </li>
        <?php endif; ?>
    </ul>

    <?php 
        switch($page_tab) {
            case 'lecturers':
                if(Auth::access('lecturer')) {
                    include(view_path('class-tab-lecturers'));
                } else {
                    $this->view('access-denied');
                }
                break;
            case 'students':
                include(view_path('class-tab-students'));
                break;
            case 'tests':
                if(Auth::access('lecturer')) {
                    include(view_path('class-tab-tests'));
                } else {
                    $this->view('access-denied');
                }
                break;
            case 'test-add':
                if(Auth::access('lecturer')) {
                    include(view_path('class-tab-tests-add'));
                } else {
                    $this->view('access-denied');
                }
                break;
            case 'test-edit':
                if(Auth::access('lecturer')) {
                    include(view_path('class-tab-tests-edit'));
                } else {
                    $this->view('access-denied');
                }
                break;
            case 'test-delete':
                if(Auth::access('lecturer')) {
                    include(view_path('class-tab-tests-delete'));
                } else {
                    $this->view('access-denied');
                }
                break;
            case 'lecturers-add':
                if(Auth::access('lecturer')) {
                    include(view_path('class-tab-lecturers-add'));
                } else {
                    $this->view('access-denied');
                }
                break;
            case 'lecturers-remove':
                if(Auth::access('lecturer')) {
                    include(view_path('class-tab-lecturers-remove'));
                } else {
                    $this->view('access-denied');
                }
                break;
            case 'students-add':
                if(Auth::access('lecturer')) {
                    include(view_path('class-tab-students-add'));
                } else {
                    $this->view('access-denied');
                }
                break;
            case 'students-remove':
                if(Auth::access('lecturer')) {
                    include(view_path('class-tab-students-remove'));
                } else {
                    $this->view('access-denied');
                }
                break;
            default:
                break;
        }
    
    ?>
    <?php else: ?>
        <h3 class="text-center">That Class was Not Found</h3>
    <?php endif; ?>
</div>
<?php $this->view("includes/footer"); ?>