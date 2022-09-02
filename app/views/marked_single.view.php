<?php $this->view("includes/header"); ?>
<?php $this->view("includes/nav_bar"); ?>
<?php //show($data); die; ?>
<div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px">
<?php $this->view("includes/crumps", $data); ?>
<?php if($row && $submited_test && $submited_test->submitted && (!$row->status)): ?>
    <h4 class="text-center"><?= esc(ucwords($row->test)) ?></h4>
    <div class="row">
        <h5 class="text-center col">From: 
            <a href="<?= URLROOT?>/single_class/<?=$row->class->class_id?>?tab=students" class="text-dark">
                <?= ucwords($row->class->class) ?>
            </a>
        </h5>
        <h5 class="text-center col">Student: 
            <a href="<?= URLROOT?>/profile/<?=$student_row->user_id?>?tab=test" class="text-dark">
            <?= ucwords($student_row->firstname) . ' '. ucwords($student_row->lastname) ?>
            </a>
        </h5>
    </div>
    
    <div class="row">
        <div class="col-4">
            <h4 class="text-center h5"><?php // esc($row->firstname)  . ' '. esc($row->lastname ) ?></h4>
        </div>
        
        <table class="table table-hover table-striped table-bordered"> 
        <tr>
            <th>Created By: 
                <td>
                    <a href="<?= URLROOT?>/profile/<?=$row->user->user_id?>?tab=test" class="text-dark">
                        <?= esc($row->user->firstname) ?> <?= esc($row->user->lastname) ?>
                    </a>
                </td>
            </th>
            <th>Date Created: <td><?= get_date($row->date) ?></td></th>
            <td>
            </td>
        </tr>
            <?php $active = $row->status ? 'No' : 'Yes'; ?>
        <tr>
            <th>Class Name:<td><?= esc($row->class->class)?></td></th>
            <td colspan="6"><b>Test Description:</b> <br> <?= esc($row->description) ?></td>
        </tr>
        </table>
        <a href="<?= URLROOT ?>/make_pdf/<?=$row->test_id?>/<?=$student_row->user_id?>">
            <button class="btn btn-primary btn-sm float-end">Save as PDF</button>
        </a>
    </div>
    <?php 
        switch($page_tab) {
            case 'view':
                include(view_path('marked-single-tab-view'));
                break;
            default:
                break;
        }
    ?>
    <?php else: ?>
        <h3 class="text-center">That test was Not Found</h3>
    <?php endif; ?>
</div>
<?php $this->view("includes/footer"); ?>