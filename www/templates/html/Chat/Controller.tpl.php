<!DOCTYPE html>
<html>
<head>
    <title><?php echo \Chat\Setting\Service::getSettingValue("SITE_NAME") ?></title>

    <?php
    $js = \Chat\PluginManager::dispatchEvent(
        \Chat\Events\CSSCompile::EVENT_NAME,
        new \Chat\Events\CSSCompile($context->output->getRawObject())
    );

    echo $savvy->render($js);
    ?>
    <!-- Bootstrap using http://bootswatch.com/cyborg/ -->
    <link href="<?php echo \Chat\Config::get('URL');?>www/templates/html/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo \Chat\Config::get('URL');?>www/templates/html/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo \Chat\Config::get('URL');?>www/templates/html/css/main.css" rel="stylesheet" media="screen">

    <?php
    $js = \Chat\PluginManager::dispatchEvent(
        \Chat\Events\JavascriptCompile::EVENT_NAME,
        new \Chat\Events\JavascriptCompile($context->output->getRawObject())
    );

    echo $savvy->render($js);
    ?>

    <script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/jquery.min.js"></script>
    <script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/jquery.cookie.js"></script>
    <script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/bootstrap.min.js"></script>
    <script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/moment.min.js"></script>
</head>
<body>
<div id='container'>
    <div id='nav-container' class='navbar row-fluid'>
        <div id='nav' class='span12'>
            <div class="navbar-inner">
                <a class="brand" href="<?php echo Chat\Config::Get('URL')?>"><?php echo \Chat\Setting\Service::getSettingValue("SITE_NAME") ?></a>

                <?php
                $mainNav = \Chat\PluginManager::dispatchEvent(
                    \Chat\Events\NavigationMainCompile::EVENT_NAME,
                    new \Chat\Events\NavigationMainCompile()
                );

                echo $savvy->render($mainNav);
                ?>
                <ul class='nav pull-right'>
                    <?php
                    if ($user) {
                        ?>
                        <li>
                            <a href='<?php echo Chat\Config::Get('URL')?>users/<?php echo $user->id ?>' id='edit-profile'><span id='edit-profile-link'><?php echo $user->username ?></span></a>
                        </li>
                        <li>
                            <form action="<?php echo \Chat\Config::get('URL') ?>logout" method="post" class="form-inline">
                                <button type="submit" name="logout" class="btn">Log Out</button>
                            </form>
                        </li>
                        <?php
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>



    <div id='main-content-container' class='row-fluid'>
        <h2><?php echo $context->output->getPageTitle() ?></h2>
        <?php

        $subNav = \Chat\PluginManager::dispatchEvent(
            \Chat\Events\NavigationSubCompile::EVENT_NAME,
            new \Chat\Events\NavigationSubCompile($context->output->getRawObject())
        );

        if (count($subNav->getNavigation())) {
            echo $savvy->render($subNav);
        }

        foreach ($context->getFlashBagMessages() as $message) {
            echo $savvy->render($message);
        }
        ?>
        <?php
        echo $savvy->render($context->output);
        ?>
    </div>

    <div id='footer-container' class='row-fluid'>
        <div id='footer' class='span12 well'>
            <div class='pull-right'>
                <a href='https://github.com/mfairchild365/chat' target='_new'>Fork on Github</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>