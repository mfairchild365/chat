var plugin_notifications = {
    notifications : [],
    visible : true,

    init: function ()
    {
        //Allow turning desktop notifications on.
        if (window.webkitNotifications && window.webkitNotifications.checkPermission() != 0) { // 0 is PERMISSION_ALLOWED
            $('#footer').prepend('<a href="#" id="show-notifications">Enable Desktop Notifications</a>');

            $('#show-notifications').click(function(e){
                if (window.webkitNotifications) {
                    window.webkitNotifications.requestPermission();
                }

                e.preventDefault();
            });
        }

        $([window, document]).blur(function () {
            plugin_notifications.visible = false;
        });

        $([window, document]).focus(function () {
            plugin_notifications.visible = true;
        });

        $(document).on('NEW_MESSAGE_ADDED', function(event, data) {
            plugin_notifications.notify('New Message', data['message']);
        });
    },

    notify: function(title, message)
    {
        if (window.webkitNotifications && window.webkitNotifications.checkPermission() == 0 && plugin_notifications.visible == false) {
            notification = window.webkitNotifications.createNotification(
                app.baseURL + 'www/templates/html/img/alert.png', title, message);

            notification.onclick = function() {
                //Focus the window.
                window.focus();

                plugin_notifications.clearNotifications();
            };

            notification.onclose = function() {
                //Focus the window.
                window.focus();

                plugin_notifications.clearNotifications();
            };

            notification.show();

            plugin_notifications.notifications.push(notification);
        }
    },

    clearNotifications: function() {
        for (id in plugin_notifications.notifications) {
            plugin_notifications.notifications[id].cancel();
        }
    }
}

$(document).on('REGISTER_PLUGINS', function(event, data) {
    plugin_notifications.init();
});