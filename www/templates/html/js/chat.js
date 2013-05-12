var app = {
    connection            : false,
    user                  : false,
    users                 : [],
    messages              : [],
    timeLoop              : false,
    messageListAutoScroll : true, //auto scroll the message list
    baseURL               : '',
    notifications         : [],
    visible               : true,

    init: function (serverAddress, baseURL)
    {
        app.baseURL = baseURL;

        try {
            app.connection = new WebSocket(serverAddress);

            app.connection.onopen = function (e) {
                $(document).trigger('SOCKET_OPEN', e);
            };
            app.connection.onmessage = function (e) {
                $(document).trigger('SOCKET_MESSAGE', e);
            };
            app.connection.onclose = function (e) {
                $(document).trigger('SOCKET_CLOSE', e);
            }
            app.connection.onerror = function (e) {
                $(document).trigger('SOCKET_ERROR', e);
            }
        } catch (ex) {
            console.log(ex);
        }

        $("#message").keypress(function(event) {
            //check if we need to submit the message.
            if (event.keyCode == 13 && !event.shiftKey) {
                //submit the message.
                app.submitMessage($("#message").val());

                //clear the message container.
                $("#message").val('');

                //Don't allow the enter key to be processed.
                event.preventDefault();
            }
        });

        $('#message-list').scroll(function(event){
            if(($('#message-list').scrollTop() + $('#message-list').height()) == $('#message-list').prop('scrollHeight')) {
                app.messageListAutoScroll = true;
            } else {
                app.messageListAutoScroll = false;
            }
        });

        $('#show-notifications').click(function(e){
            if (window.webkitNotifications) {
                window.webkitNotifications.requestPermission();
            }

            e.preventDefault();
        });


        if (window.webkitNotifications && window.webkitNotifications.checkPermission() != 0) { // 0 is PERMISSION_ALLOWED
            $('#show-notifications').css('visibility', 'visible');
        }

        $([window, document]).blur(function () {
            app.visible = false;
        });

        $([window, document]).focus(function () {
            app.visible = true;
        });

        //Core Event Watchers
        $(document).on('USER_CONNECTED', function(event, data) {
            //Add the user to our internal users array.
            app.users[data['Chat\\User\\User']['id']] = data['Chat\\User\\User'];

            if (app.users[data['Chat\\User\\User']['id']]['chat_status'] == 'ONLINE') {
                app.addUser(data['Chat\\User\\User']);
            }
        });

        $(document).on('USER_DISCONNECTED', function(event, data) {
            app.removeUser(data['Chat\\User\\User']);
        });

        $(document).on('USER_INFORMATION', function(event, data) {
            app.user = data['Chat\\User\\User'];

            $.cookie('lan', app.user['id'], { path: '/' });

            if (app.user.name == "UNKNOWN") {
                $('#edit-profile-modal').modal();
            }

            $('#edit-profile-link').html(app.user['name']);

            var elementId = app.getUserElementId(app.user);

            $('#' + elementId).removeClass('them');
            $('#' + elementId).addClass('me');
        });

        $(document).on('USER_UPDATED', function(event, data) {
            app.updateUser(data['Chat\\User\\User']);

            //Update the internal user,
            app.users[data['Chat\\User\\User']['id']] = data['Chat\\User\\User'];
        });

        $(document).on('MESSAGE_NEW', function(event, data) {
            app.addMessage(data['Chat\\Message\\Message']);

            //Add the message to the internal list of messages.
            app.messages[data['Chat\\Message\\Message']['id']] = data['Chat\\Message\\Message'];
        });

        $(document).on('SOCKET_OPEN', function(event, data) {
            console.log("Connection established!");
            $("#connection-status").removeClass('badge-important');
            $("#connection-status").addClass('badge-success');
            $("#connection-status").html("Online");

            $("#message").removeAttr('disabled');
        });

        $(document).on('SOCKET_MESSAGE', function(event, data) {
            data = JSON.parse(data.data);

            if (data['action'] == undefined) {
                console.log('Error: No action provided');
                return;
            }

            $(document).trigger(data['action'], data['data']);
        });

        $(document).on('SOCKET_CLOSE', function(event, data) {
            console.log(data.data);

            $('#error-modal-alert-text').html("There was an error and you have been disconnected.");
            $('#error-modal').modal('show');

            $("#message").attr('disabled', 'disabled');

            $("#connection-status").removeClass('badge-success');
            $("#connection-status").addClass('badge-important');
            $("#connection-status").removeClass('badge-warning');
            $("#connection-status").html("Offline");
        });

        $(document).on('SOCKET_ERROR', function(event, data) {
            console.log("Error");

            app.onClose(data);

            alert(data.data);
        });

        app.timeLoop = setInterval('app.updateMessageTimes()', 1000);
    },

    /**
     * Actions:
     *   -- UPDATE_USER (user object)
     *   -- SEND_CHAT_MESSAGE (text object)
     */
    send: function(action, object)
    {
        data = { };

        data['action'] = action;

        if (object == undefined) {
            object = [];
        }

        data['data']   = object;

        app.connection.send(JSON.stringify(data));
    },

    addMessage: function(message)
    {
        var userClass = 'them';

        if (message['users_id'] == app.user['id']) {
            userClass = 'me';
        }

        var time = moment(message['date_created']).fromNow()

        $('#message-list').append(
            "<li id='message-" + message['id'] + "' class='" + userClass + "'>" +
                "<span class='avatar user-" + message['users_id'] + "'></span>"
                + message['message'] + "" +
                "<div class='info'>" +
                    "<span class='user user-" + message['users_id'] + "'>" + app.users[message['users_id']]['username'] + "</span>" +
                    "<span class='message-date'>" + time + "</span>" +
                "</div>" +
            "</li>");

        app.scrollMessages();

        if (window.webkitNotifications && window.webkitNotifications.checkPermission() == 0 && app.visible == false) {
            // function defined in step 2

            notification = window.webkitNotifications.createNotification(
                app.baseURL + 'img/alert.png', 'LAN: New Message', message['message']);

            notification.onclick = function() {
                //Focus the window.
                window.focus();

                app.clearNotifications();
            };

            notification.onclose = function() {
                //Focus the window.
                window.focus();

                app.clearNotifications();
            };

            notification.show();

            app.notifications.push(notification);
        }
    },

    addUser: function(user)
    {
        var elementId = app.getUserElementId(user);

        //Only append if it does not already exist
        if ($('#' + elementId).length != 0) {
            return;
        }

        var html = "<li id='" + elementId + "'>" +
                       "<ul>" +
                            "<li><span class='user-name'>" + user['username'] + "</span></li>" +
                        "</ul>" +
                   "</li>";

        $('#user-list').append(html);

        $('#' + elementId).addClass('them');
    },

    removeUser: function(user)
    {
        var elementId = app.getUserElementId(user);

        //Only append if it does not already exist
        if ($('#' + elementId).length == 0) {
            return;
        }

        $('#' + elementId).remove();
    },

    updateUser: function(user)
    {
        var elementId = app.getUserElementId(user);

        $('#' + elementId + " .user-name").html(user['name']);

        $('.user-' + user['id']).html(user['name']);

        //Update the client user if we need to.
        if (user['id'] == app.user['id']) {
            app.user = user;
            $('#edit-profile-link').html(app.user['name']);
        }
    },

    getUserElementId: function(user) {
        return 'LAN-User-Record-' + user['id'];
    },

    submitMessage: function(message)
    {
        if (message == undefined) {
            return false;
        }

        app.send('SEND_CHAT_MESSAGE', message);
    },

    updateMessageTimes: function()
    {
        for (id in app.messages){

            var time = moment(app.messages[id]['date_created']).fromNow()

            $('#message-' + id + " .message-date").html(time);
        }

    },

    scrollMessages:function () {
        if (app.messageListAutoScroll) {
            $("#message-list").scrollTop($("#message-list").prop('scrollHeight'));
        }
    },

    clearNotifications: function() {
        for (id in app.notifications) {
            app.notifications[id].cancel();
        }
    }
};