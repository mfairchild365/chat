<div class='row-fluid'>
    <div class="alert">
        <strong>Warning!</strong> You need to log in to view this site.
    </div>
</div>

<div class='row-fluid'>
    <div class="span6">
        <?php echo $savvy->render(new \Chat\User\Login()); ?>
    </div>

    <div class="span6">
        <?php echo $savvy->render(new \Chat\User\Register()); ?>
    </div>
</div>