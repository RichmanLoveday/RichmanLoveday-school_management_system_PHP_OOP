<table class="table table-striped table-hover">
            <?php //show($data); die; ?>
            <tr>
                <th></th>
                <th>Class Name</th>
                <th>Created By</th>
                <th>Date</th>
                <th>Action</th>
            </tr>

        <?php if(isset($rows) && $rows):
            //show($rows); die; 
                
            foreach($rows as $row): 
                //$row = $row[0];
            ?>
                <tr>
                    <td>
                        <a href="<?= URLROOT ?>/single_class/<?= $row->class_id ?>?tab=students">
                            <button class="btn btn-primary btn-sm"><i class="fa fa-chevron-right"></i></button>
                        </a>
                    </td>
                        <td><?= $row->class ?></td>
                        <td><?= $row->user->firstname . ' ' . $row->user->lastname ?></td>
                        <td><?= get_date($row->date ) ?></td>
                    <td>
                        <?php if(Auth::access('lecturer')): ?>
                            <a href="<?= URLROOT ?>/classes/edit/<?= $row->id ?>">
                                <button class="btn btn-info text-white btn-sm"><i class="fa fa-edit"></i></button>
                            </a>
                                    
                            <a href="<?= URLROOT ?>/classes/delete/<?= $row->id ?>">
                                <button class="btn btn-danger text-white btn-sm"><i class="fa fa-trash"></i></button>
                            </a>  
                        <?php endif; ?>  
                    </td>
                </tr>  
        <?php endforeach; ?>
    <?php else: ?>
     <tr><td colspan="5"><center>No classes were found at this time</center></td></tr>   
    <?php endif; ?>
</table>