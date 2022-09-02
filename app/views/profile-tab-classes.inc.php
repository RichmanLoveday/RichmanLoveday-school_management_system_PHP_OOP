<nav class="navbar navbar-light bg-light">
        <form class="form-inline">
            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search "></i>&nbsp;</span>
            </div>
            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon1">
            </div>
        </form>
</nav>

<?php
    $rows = $user_class;
?>
<div class="card-group justify-content-center mx-auto w-auto">
    <?php include(view_path('class')) ?>
</div>