<?php $this->view("includes/header"); ?>
<?php $this->view("includes/nav_bar"); ?>
<?php //show($data);  ?>
<div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px">
<?php $this->view("includes/crumps", $data); ?>
<?php if($row):
    $row = $row[0]; ?>

    <h4 class="text-center"><?= ucwords($row->test) ?></h4>
    <div class="row"> 
        <div class="col-4">
            <h4 class="text-center h5"><?php // esc($row->firstname)  . ' '. esc($row->lastname ) ?></h4>
        </div>
        
        <table class="table table-hover table-striped table-bordered"> 
        <tr>
            <th>Class Name:<td><?= esc($row->test)?></td></th>
            <th>Created By: <td><?= esc($row->user->firstname) ?> <?= esc($row->user->lastname) ?></td></th>
            <th>Date Created: <td><?= get_date($row->date) ?></td></th>
            <td>
                <a href="<?= URLROOT ?>/single_class/<?= $row->class_id?>?tab=tests">
                    <button class="btn btn-primary text-white btn-sm"><i class="fa fa-chevron-right me-2"></i>View Test</button>
                </a>

                <a href="<?= URLROOT ?>/single_test/<?= $row->test_id?>?tab=scores">
                    <button class="btn btn-primary text-white btn-sm"><i class="fa fa-chevron-right me-2"></i>Student Scores</button>
                </a>
            </td>
        </tr>
            <?php 
                $active = $row->status ? 'No' : 'Yes'; 
            ?>
        <tr>
            <td>
                <b>Published:</b> <?= esc($active) ?> <br>
                <?php
                    $publish = 'Unpublish';
                    $btncolor = 'btn-primary';
                    if($row->status) {
                        $publish = 'Publish';
                        $btncolor = 'btn-danger';
                    }
                ?>

                <?php if(empty($student_scores)): ?>
                    <a href="<?= URLROOT ?>/single_test/<?= $row->test_id?>?status=true">
                        <button type="button" class="btn btn-sm <?= $btncolor ?> mt-2"><?= $publish ?></button>
                    </a>
                <?php else: ?>
                    <button disabled type="button" class="btn btn-sm <?= $btncolor ?> mt-2"><?= $publish ?></button>
                <?php endif; ?>

            </td>
            <td colspan="6"><b>Test Description:</b> <br> <?= esc($row->description) ?></td>
        </tr>
        
        </table>
    </div>

    <?php 
        switch($page_tab) {
            case 'view':
                include(view_path('test-tab-view'));
                break;
            case 'edit':
                include(view_path('test-tab-edit'));
                break;
            case 'edit-question':
                include(view_path('test-tab-edit-question'));
                break;
            case 'delete-question':
                include(view_path('test-tab-delete-question'));
                break;
            case 'add-question':
                include(view_path('test-tab-add-question'));
                break;
            case 'delete':
                include(view_path('test-tab-delete'));
                break;
            case 'scores':
                include(view_path('test-tab-scores'));
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