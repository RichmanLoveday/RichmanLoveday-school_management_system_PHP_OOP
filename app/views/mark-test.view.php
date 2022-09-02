<?php $this->view("includes/header"); ?>
<?php $this->view("includes/nav_bar"); ?>
<?php //show($data); die; ?>
<div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px">
<?php $this->view("includes/crumps", $data); ?>
<?php if($row && $submited_test && $submited_test->submitted && (!$row->status)): ?>
    <h4 class="text-center"><?= ucwords($row->test) ?></h4>
    <div class="row">
        <h5 class="text-center col">From: <?= ucwords($row->class->class) ?></h5>
        <h5 class="text-center col">Student: <?= ucwords($student_row->firstname) . ' '. ucwords($student_row->lastname) ?> </h5>
    </div>
    
    <div class="row">
        <div class="col-4">
            <h4 class="text-center h5"><?php // esc($row->firstname)  . ' '. esc($row->lastname ) ?></h4>
        </div>
        
        <table class="table table-hover table-striped table-bordered"> 
        <tr>
            <th>Created By: <td><?= esc($row->user->firstname) ?> <?= esc($row->user->lastname) ?></td></th>
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
    </div>
    <?php 
        switch($page_tab) {
            case 'view':
                include(view_path('mark-test-tab-view'));
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