<?php
foreach ($context->getScripts() as $url)
{
    ?>
    <link href="<?php echo $url ?>" rel="stylesheet" media="screen">
    <?php
}
?>