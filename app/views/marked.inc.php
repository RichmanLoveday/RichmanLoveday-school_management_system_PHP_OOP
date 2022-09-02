<div class="table-responsive w-100">
    <table class="table table-striped table-hover">
        <?php //show($data);  ?>
        <tr>
            <th></th>
            <th>Test Name</th>
            <th>Student</th>
            <th>Marked By</th>
            <th>Date Submitted</th>
            <th>Date Marked</th>
            <th>Answered</th>
            <th>Score</th>
            <th></th>
        </tr>

        <?php //show($data);
        if(isset($test_rows) && $test_rows):
                //show($test_rows); die; 
            foreach($test_rows as $key => $value):  
                    foreach($test_rows[$key] as $key1 => $row): 
                    $active = $row->test_detail->status ? 'No' : 'Yes'; ?>
                        <tr>
                            <td>
                               
                            </td>
                            
                            <td><?= $row->test_detail->test ?></td>
                            <td><?= ucfirst($row->user->firstname) . ' '. ucfirst($row->user->lastname) ?></td>
                            <td><?=ucfirst($row->marked_by->firstname)?> <?=ucfirst($row->marked_by->lastname)?></td>
                            <td><?= get_date($row->submitted_date) ?></td>
                            <td><?= get_date($row->date_marked) ?></td>
                            <td>
                                <?php $percentage = get_answered_percentage($row->test_id, $row->user_id); ?>
                                <?=$percentage?>% 
                            </td>

                            <td>
                                <?php $percentage_scored = get_score_percentage($row->test_id, $row->user_id); ?>
                                <?=$percentage_scored?>% 
                            </td>
                            <td>
                                <a href="<?= URLROOT ?>/marked_single/<?= $row->test_id ?>/<?= $row->user->user_id ?>">
                                    <button class="btn btn-sm btn-primary">View<i class="fa fa-arrow-right ms-2"></i></button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php else: ?>
        <tr><td colspan="9"><center>No tests were found at this time</center></td></tr>   
        <?php endif; ?>
    </table>
</div>
