<nav class="navbar navbar-light bg-light">
    <form method="get" class="form-inline ms-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;"><i class="fa fa-search "></i>&nbsp;</span>
            </div>
            <input type="text" class="form-control" name="find" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
        </div>
    </form>
    <a href="<?= URLROOT ?>/single_class/testadd/ <?= $row->class_id ?>" class="me-3">
        <button class="btn btn-sm btn-primary"><i class="fa fa-plus me-2"></i>Add Test</button>
    </a>
</nav>


<table class="table table-striped table-hover">
        <?php //show($data); ?>
        <tr>
            <th></th>
            <th>Test Name</th>
            <th>Created By</th>
            <th>Active</th>
            <th>Taken</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
<?php //show($data); //die; ?>
        <?php if(isset($tests) && $tests):
        //show($rows); die; 
            
        foreach($tests as $row): 
        $active = $row->status ? 'No' : 'Yes';
        ?>
            <tr>
                <td>
                    <a href="<?= URLROOT ?>/single_test/<?= $row->test_id ?>">
                        <button class="btn btn-primary btn-sm"><i class="fa fa-chevron-right"></i></button>
                    </a>
                </td>
                    <td><?= $row->test ?></td>
                    <td><?= $row->user->firstname . ' ' . $row->user->lastname ?></td>
                    <td><?= $active ?></td>
                    <td><?= has_taken_test($row->test_id); ?></td>
                    <td><?= get_date($row->date ) ?></td>
                <td>
                    <?php if(Auth::access('lecturer')): ?>
                        <a href="<?= URLROOT ?>/single_class/testedit/<?= $row->class_id  ?>/<?= $row->test_id?>?tab=tests">
                            <button class="btn btn-info text-white btn-sm"><i class="fa fa-edit"></i></button>
                        </a>
                                
                        <a href="<?= URLROOT ?>/single_class/testdelete/<?= $row->class_id ?>/<?= $row->test_id?>?tab=tests">
                            <button class="btn btn-danger text-white btn-sm"><i class="fa fa-trash"></i></button>
                        </a>  
                    <?php endif; ?>  
                </td>
            </tr>       
        <?php endforeach; ?>
    <?php else: ?>
     <tr><td colspan="7"><center>No classes were found at this time</center></td></tr>   
    <?php endif; ?>
</table>