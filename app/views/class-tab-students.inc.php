<nav class="navbar navbar-light bg-light">
    <form class="form-inline ms-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;" id="basic-addon1"><i class="fa fa-search "></i>&nbsp;</span>
            </div>
            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
        </div>
    </form>
    <?php if(Auth::access('lecturer')): ?>
    <div class="float-right">
        <a href="<?= URLROOT ?>/single_class/studentsadd/<?= $row->class_id ?>?select=true" class="me-3">
            <button class="btn btn-sm btn-primary"><i class="fa fa-plus me-2"></i>Add Lecturer</button>
        </a>
        <a href="<?= URLROOT ?>/single_class/studentsremove/<?= $row->class_id ?>?select=true" class="me-3">
            <button class="btn btn-sm btn-primary"><i class="fa fa-minus me-2"></i>Remove</button>
        </a>
    </div>
    <?php endif; ?>
    
</nav>
<div class="card-group justify-content-center">
    <?php if(is_array($students)): ?>
        <?php foreach($students as $student):
            $row = $student->user;  
        ?>
            <?php include(view_path('includes/user')) ?>
        <?php endforeach; ?>
        <?php $pager->display(); ?>
    <?php else: ?>
    <center><h4>No Student was found in this class</h4></center>
    <?php endif ?>
</div>

<?php //show($lecturers); die; ?>