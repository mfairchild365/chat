<!DOCTYPE html>
<html>
<head>
    <title>LAN</title>
    <!-- Bootstrap using http://bootswatch.com/cyborg/ -->
    <link href="<?php echo \Chat\Config::get('URL');?>www/templates/html/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo \Chat\Config::get('URL');?>www/css/main.css" rel="stylesheet" media="screen">

    <script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/jquery.min.js"></script>
    <script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/jquery.cookie.js"></script>
    <script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/bootstrap.min.js"></script>
    <script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/moment.min.js"></script>
    <script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/main.js"></script>
</head>
<body>
<div id='container'>
    <div id='nav-container' class='navbar row-fluid'>
        <div id='nav' class='span12'>
            <div class="navbar-inner">
                <a class="brand" href="#">LAN</a>

                <ul class="nav">
                    <li class="active"><a href="#">Home</a></li>
                </ul>
                <ul class='nav pull-right'>
                    <li><a href='#' id='edit-profile'><span id='edit-profile-link'>Your Name</span> (Edit Profile)</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div id='main-content-container' class='row-fluid'>
        <div id='main-content' class='span12'>
        <?php
        echo $savvy->render($context->output);

        ?>
        </div>
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