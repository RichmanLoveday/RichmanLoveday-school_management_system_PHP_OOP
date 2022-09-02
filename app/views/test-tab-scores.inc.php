<div class="table-responsive">
    <a href="<?= URLROOT ?>/single_test/<?= $row->test_id?>">
        <button class="btn btn-sm float-end btn-primary mx-2 my-2">
            <i class="fa fa-arrow-left me-2"></i>Back
        </button>
    </a>
    
    <table class="table table-stripped table-hover caption-top">
        <caption>Student Scores</caption>
        <tr>
            <th>Student Name</th>
            <th>Scores</th>
        </tr>
        <?php if($student_scores): ?>
            <?php foreach($student_scores as $score): ?>
                <tr>
                    <td><?= $score->user->firstname?> <?= $score->user->lastname ?></td>
                    <td><?= $score->score?>%</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
