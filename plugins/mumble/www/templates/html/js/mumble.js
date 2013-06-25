var plugin_mumble = {

    init: function ()
    {
        $(document).on('MUMBLE_USER_INFO', function(event, data) {
            for (var i = 0; i < data.length; i++) {
                var mumbleUser = data[i];
                plugin_mumble.updateUserStatus(mumbleUser);
            }
        });
    },

    updateUserStatus: function(mumbleUser) {
        var selector = plugin_mumble.getUserSelector(mumbleUser);

        var channel = "";

        if (mumbleUser.status == 'online') {
            channel = "(" + mumbleUser.channelName + ")";
        }

        selector.html(
            "<span class='label'>" +
                "<strong>Mumble:</strong> " + mumbleUser.name  + " - " + plugin_mumble.getTextualStatus(mumbleUser) + " " + channel +
            "</span>"
        );
    },

    getUserSelector: function(mumbleUser)
    {
        if (mumbleUser.users_id) {
            if ($("#User-Record-" + mumbleUser.users_id + " ul .mumble").length == 0) {
                $("#User-Record-" + mumbleUser.users_id + " ul").append("<li class='mumble'></li>");
            }

            return $("#User-Record-" + mumbleUser.users_id + " ul .mumble");
        }

        if ($("#mumble-user-list").length == 0) {
            $("#other-info").append("<h3>Other Mumble Users</h3><ul id='mumble-user-list'></ul>");
        }

        if ($("#mumble-user-list .mumble-user-" + mumbleUser.name).length == 0) {
            $("#mumble-user-list").append("<li class='mumble-user-" + mumbleUser.name + "'></li>");
        }

        return $("#mumble-user-list .mumble-user-" + mumbleUser.name);
    },

    getListSelector: function(mumbleUser)
    {
        if (mumbleUser.users_id) {
            return $("#User-Record-" + info.users_id + " ul");
        }

        return $("#mumble-user-list");
    },

    getTextualStatus: function(mumbleUser)
    {
        if (mumbleUser.status == 'offline') {
            return 'Offline';
        }

        if ((mumbleUser.mute || mumbleUser.selfMute) && (mumbleUser.mute || mumbleUser.deaf)) {
            return 'Mute+Deaf';
        }

        if (mumbleUser.mute || mumbleUser.deaf) {
            return 'Deaf';
        }

        if (mumbleUser.mute || mumbleUser.deaf) {
            return 'Mute';
        }

        return 'Online';
    }
}

$(document).on('REGISTER_PLUGINS', function(event, data) {
    plugin_mumble.init();
});