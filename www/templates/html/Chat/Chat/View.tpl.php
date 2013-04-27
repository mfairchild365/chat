<script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/chat.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        app.init("ws://<?php echo \Chat\Config::get('SERVER_ADDR'); ?>:<?php echo \Chat\Config::get('SERVER_PORT'); ?>/chat", '<?php echo \Chat\Config::get('URL')?>');
    });
</script>