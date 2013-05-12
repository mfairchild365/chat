<?php
foreach ($context->getScripts() as $url)
{
    ?>
    <script src="<?php echo $url;?>"></script>
    <?php
}
?>