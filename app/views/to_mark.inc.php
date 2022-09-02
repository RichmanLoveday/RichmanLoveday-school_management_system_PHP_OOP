<div class="table-responsive w-100">
    <table class="table table-striped table-hover">
        <?php //show($data); die; ?>
        <tr>
            <th></th>
            <th>Test Name</th>
            <th>Student</th>
            <th>Date Submitted</th>
            <th>Answered</th>
            <th>Marked</th>
            <th></th>
        </tr>

        <?php //show($data);
        if(isset($test_rows) && $test_rows):
            foreach($test_rows as $key => $row): 
                $active = $row->test_detail->status ? 'No' : 'Yes'; ?>
                <tr>
                    <td>
                        <?php if(Auth::access('lecturer')): ?>
                            <a href="<?= URLROOT ?>/mark_test/<?= $row->test_id?>/<?= $row->user->user_id ?>">
                                <button class="btn btn-primary btn-sm">Mark this test<i class="ms-2 fa fa-chevron-right"></i></button>
                            </a>
                        <?php endif; ?>
                    </td>
                    
                    <td><?= $row->test_detail->test ?></td>
                    <td><?= ucfirst($row->user->firstname) . ' '. ucfirst($row->user->lastname) ?></td>
                    <td><?= get_date($row->submitted_date) ?></td>
                    <td>
                        <?php $percentage = get_answered_percentage($row->test_id, $row->user_id); ?>
                        <?=$percentage?>% 
                    </td>

                    <td>
                        <?php $percentage_marked = get_marked_percentage($row->test_id, $row->user_id); ?>
                        <?=$percentage_marked?>% 
                    </td>

                    <td>
                        <?php if(can_take_test($row->test_id)): ?>
                            <a href="<?= URLROOT ?>/take_test/<?= $row->test_id ?>">
                                <button class="btn btn-sm btn-primary">Take this test</button>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
        <tr><td colspan="6"><center>No tests were found at this time</center></td></tr>   
        <?php endif; ?>
    </table>
</div>


