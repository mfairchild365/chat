<script src="<?php echo \Chat\Config::get('URL');?>www/templates/html/js/chat.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        app.init("ws://<?php echo \Chat\Config::get('SERVER_ADDR'); ?>:<?php echo \Chat\Config::get('SERVER_PORT'); ?>/chat", '<?php echo \Chat\Config::get('URL')?>');
    });
</script>

<div class='span8 well'>
    <div id='chat-container'>
        <ul id='message-list'>

        </ul>
        <textarea cols='6' class='span9' id='message' disabled='disabled'></textarea>
    </div>
</div>

<div class='span3 well'>
    <h2>Users</h2>
    <ul id='user-list'>

    </ul>
</div>