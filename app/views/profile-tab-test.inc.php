<h3>Test</h3>
<nav class="navbar navbar-light bg-light">
    <form class="form-inline">
        <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><i class="fa fa-search "></i>&nbsp;</span>
        </div>
        <input name="find" type="text" value="<?= get_var('find') ?>" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
        <input type="hidden" name="tab" value="test">
        </div>
    </form>
</nav>
<?php //show($test_rows)?>

<?php
    if($rows->rank == 'student') {
        include(view_path('marked'));
    } else {
        include(view_path('tests'));
    } 
?>

