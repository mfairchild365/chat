var plugin_gravatar = {
    base_url : "http://www.gravatar.com/avatar/",

    init: function ()
    {

        $(document).on('MESSAGE_NEW', function(event, data) {
            var messageID = "#message-" + data['Chat\\Message\\Message']['id'];
            var users_id = data['Chat\\Message\\Message']['users_id'];

            if (core_chat.users[users_id] == undefined) {
                return;
            }

            var email = core_chat.users[users_id].email;

            $(messageID + " .avatar").html("<img src='" +  plugin_gravatar.getProfileImage(email, 30) + "' />");
        });

        $(document).on('USER_INFORMATION', function(event, data) {
            $('#user-nav').prepend('<li id="user-avatar"><a href="https://en.gravatar.com/site/login" target="_blank"><img src="' + plugin_gravatar.getProfileImage(app.user.email, 30)  + '" /></a></li>');
            $('#user-avatar a').css('padding-right', '0px');
        });

        $(document).on('USER_CONNECTED', function(event, data) {
            var user = data['Chat\\User\\User'];
            var element = core_chat.getUserElementId(user);
            $('#' + element + ' .avatar').html('<img src="' + plugin_gravatar.getProfileImage(user.email, 30)  + '" />');
        });

    },

    getProfileImage : function (email, size) {
        email = hex_md5(email);

        if (size == undefined) {
            size = "40";
        }

        return plugin_gravatar.base_url + email + "?s=" + size;
    }
}

$(document).on('REGISTER_PLUGINS', function(event, data) {
    plugin_gravatar.init();
});