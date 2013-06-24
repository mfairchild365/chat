<?php
$user = \Chat\User\Service::getCurrentUser();
if ($user->mumble_name) {
    echo "You are connected as " . $user->mumble_name;
}
?>


<form action="<?php echo $context->getEditURL()?>" method="post" class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="input-mumble-name">Mumble Name</label>
        <div class="controls">
            <input type="text" id="input-mumble-name" name="mumble_name" placeholder="exactly as it appears in mumble" value="<?php echo $user->mumble_name?>" required />
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn">Update</button>
        </div>
    </div>
</form>