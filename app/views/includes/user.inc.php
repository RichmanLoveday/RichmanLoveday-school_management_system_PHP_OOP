<div class="card m-2 shadow-lg d-block mx-auto" style="max-width: 12rem; min-width: 12rem;">
<?php $image = get_image($row->image, $row->gender);?>
    <img class="card-img-top w-75 rounded-circle mx-auto d-block mx-auto border mt-1" src="<?= $image ?> " alt="no_image">
        <div class="card-body">
            <h5 class="card-title"><?= $row->firstname ?></h5>
            <p class="card-text"><?= str_replace("_", ' ', $row->rank) ?></p>
            <a href="<?= URLROOT ?>/profile/<?= $row->user_id ?>" class="btn btn-sm text-white btn-primary">
                    Profile
            </a>
            
            <?php if(isset($_GET['select'])): ?>
                <button type="submit" value="<?= $row->user_id ?>" name="selected" class="btn text-white btn-sm btn-secondary float-end">Select</button>
            <?php endif; ?>
            
        </div>
</div>