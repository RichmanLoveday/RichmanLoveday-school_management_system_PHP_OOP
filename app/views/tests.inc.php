<div class="table-responsive w-100">
    <table class="table table-striped table-hover">
        <?php //show($data);  ?>
        <tr>
            <th></th>
            <th>Test Name</th>
            <th>Created By</th>
            <th>Active</th>
            <th>Date</th>
            <th>Answered</th>
            <th></th>
        </tr>

        <?php //show($data);

            if(isset($test_rows) && $test_rows):
                    //show($test_rows); 
                    foreach($test_rows as $row):
                        $active = $row->status ? 'No' : 'Yes'; 
                        if(isset($unsubmitted_test)) {
                            $change_bg = in_array($row->test_id, $unsubmitted_test) ? 'background-color: #eebebe;' : '';
                        }
                        
                        ?>
                            <tr style="<?= (Auth::getRank() == 'student')  ? $change_bg : '' ?>">
                                <td>
                                    <?php if(Auth::access('lecturer')): ?>
                                        <a href="<?= URLROOT ?>/single_test/<?= $row->test_id ?>">
                                            <button class="btn btn-primary btn-sm"><i class="fa fa-chevron-right"></i></button>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                
                                <td><?= $row->test ?></td>
                                <td><?= $row->user->firstname . ' ' . $row->user->lastname ?></td>
                                <td><?= $active ?></td>
                                <td><?= get_date($row->date) ?></td>
                                <td>
                                    <?php $my_id = get_class($this) == 'Profile' ? $row->user_id : Auth::getUser_id(); ?>
                                    <?php $percentage = get_answered_percentage($row->test_id, $my_id); ?>
                                    <?=$percentage?>% 
                                </td>

                                <td>
                                    <?php if(can_take_test($row->test_id)): ?>
                                        <a href="<?= URLROOT ?>/take_test/<?= $row->test_id ?>">
                                            <button class="btn btn-sm btn-primary">Take this test</button>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                    <?php endforeach ?>
            <?php else: ?>
            <tr><td colspan="7"><center>No tests were found at this time</center></td></tr>   
        <?php endif; ?>
    </table>
</div>
