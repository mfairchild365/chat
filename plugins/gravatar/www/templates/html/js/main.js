var plugin_gravatar = {
    base_url : "http://www.gravatar.com/avatar/",

    init: function ()
    {

        $(document).on('MESSAGE_NEW', function(event, data) {
            var messageID = "#message-" + data['Chat\\Message\\Message']['id'];
            var users_id = data['Chat\\Message\\Message']['id'];

            if (app.users[data['Chat\\Message\\Message']['id']] == undefined) {
                return;
            }

            var email = app.users[data['Chat\\Message\\Message']['users_id']].email;

            $(messageID + " .avatar").html("<img src='" +  plugin_gravatar.getProfileImage(email, 40) + "' />");
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