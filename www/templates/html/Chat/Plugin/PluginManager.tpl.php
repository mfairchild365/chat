<form action="<?php echo $context->getEditURL()?>" method="post" class="form-horizontal">
    <?php
    foreach (\Chat\Plugin\PluginManager::getExternalPlugins() as $name=>$plugin)
    {
        ?>
        <div class="controls">
            <h3><?php echo $plugin->getName(); ?></h3>
            <p>
                <?php echo $plugin->getDescription() ?>
            </p>

            <label class="checkbox">
                <input type="checkbox" name="enabled_plugins[]" value="<?php echo $name ?>"> Enable
            </label>

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