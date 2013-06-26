var plugin_oembed = {

    init: function ()
    {
        $(document).on('MESSAGE_ADDED', function(event, message) {
            console.log(message);
            $("#message-" + message.id + " a").embedly({key: 'Your Embedly Key'});
        });
    }
}

$(document).on('REGISTER_PLUGINS', function(event, data) {
    plugin_oembed.init();
});