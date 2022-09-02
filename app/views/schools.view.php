<?php $this->view('includes/header'); ?>
<?php $this->view('includes/nav_bar');

?>
        <div class="container p-4 shadow mx-auto" style="margin-top: 80px; max-width: 1000px;">
        <?php $this->view("includes/crumps", $data); ?>
            <div class="card-group justify-content-center mx-auto w-auto">
                
                <table class="table table-striped table-hover">
                    <?php //echo '<pre>';
                            //print_r($data);
                            //echo '</pre>'; ?>
                    <tr>
                        <th></th>
                        <th>School</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>
                            <a href="<?= URLROOT ?>/schools/add">
                            <button class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>Add New
                            </button>
                            </a>
                            
                        </th>
                    </tr>

                <?php if($rows):
                    foreach($rows as $row): ?>

                        <tr>
                            <td><button class="btn btn-primary btn-sm"><i class="fa fa-chevron-right"></i></button></td>
                            <td><?= $row->school ?></td>
                            <td><?= $row->user->firstname . ' ' . $row->user->lastname ?></td>
                            <td><?= get_date($row->date ) ?></td>
                            <td>
                                <a href="<?= URLROOT ?>/schools/edit/<?= $row->id ?>">
                                    <button class="btn btn-info text-white btn-sm"><i class="fa fa-edit"></i></button>
                                </a>
                                
                                <a href="<?= URLROOT ?>/schools/delete/<?= $row->id ?>">
                                    <button class="btn btn-danger text-white btn-sm"><i class="fa fa-trash"></i></button>
                                </a>

                                <a href="<?= URLROOT ?>/switch_school/<?= $row->id ?>">
                                    <button class="btn btn-success text-white btn-sm">Switch to<i class="fa fa-chevron-right ms-2"></i></button>
                                </a>
                                
                            </td>
                        </tr>
                       
                <?php endforeach; ?>
               <?php else: ?>
            <h4>No schools were found at this time</h4>
            <?php endif; ?>
            </table>
           </div>
             
        </div>

        <?php

        ?>
<?php $this->view('includes/footer'); ?>
   