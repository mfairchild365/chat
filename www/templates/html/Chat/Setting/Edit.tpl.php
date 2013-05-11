<h2>Settings</h2>


<form action="<?php echo $context->getEditURL()?>" method="post" class="form-horizontal">
    <?php
    foreach (\Chat\Setting\Settings::getAllSettings() as $setting)
    {
        ?>
        <div class="control-group">
        <label class="control-label" for="input-<?php echo $setting->setting_name; ?>"><?php echo $setting->setting_name; ?></label>
        <div class="controls">
            <input type="text" id="input-firstname" name="settings[<?php echo $setting->id; ?>]" placeholder="<?php echo $setting->setting_name; ?>" value="<?php echo $setting->setting_value; ?>" />
        </div>
        </div>
        <?php
    }
    ?>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn">Update</button>
        </div>
    </div>
</form>