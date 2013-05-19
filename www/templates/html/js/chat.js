var core_chat = {
    messages              : [],
    timeLoop              : false,
    messageListAutoScroll : true, //auto scroll the message list

    init: function ()
    {
        $("#message").keypress(function(event) {
            //check if we need to submit the message.
            if (event.keyCode == 13 && !event.shiftKey) {
                //submit the message.
                core_chat.submitMessage($("#message").val());

                //clear the message container.
                $("#message").val('');

                //Don't allow the enter key to be processed.
                event.preventDefault();
            }
        });

        $('#message-list').scroll(function(event){
            if(($('#message-list').scrollTop() + $('#message-list').height()) == $('#message-list').prop('scrollHeight')) {
                core_chat.messageListAutoScroll = true;
            } else {
                core_chat.messageListAutoScroll = false;
            }
        });

        //Core Event Watchers
        $(document).on('USER_CONNECTED', function(event, data) {
            core_chat.addUser(data['Chat\\User\\User']);
        });

        $(document).on('USER_DISCONNECTED', function(event, data) {
            app.setUserStatus(data['Chat\\User\\User']);
        });

        $(document).on('USER_INFORMATION', function(event, data) {
            var elementId = core_chat.getUserElementId(app.user);

            $('#' + elementId).removeClass('them');
            $('#' + elementId).addClass('me');
        });

        $(document).on('USER_UPDATED', function(event, data) {
            core_chat.updateUser(data['Chat\\User\\User']);

            //Update the internal user,
            app.users[data['Chat\\User\\User']['id']] = data['Chat\\User\\User'];
        });

        $(document).on('MESSAGE_NEW', function(event, data) {
            core_chat.addMessage(data['Chat\\Message\\Message']);

            //Add the message to the internal list of messages.
            core_chat.messages[data['Chat\\Message\\Message']['id']] = data['Chat\\Message\\Message'];
        });

        $(document).on('SOCKET_OPEN', function(event, data) {
            $("#message").removeAttr('disabled');
        });

        $(document).on('SOCKET_CLOSE', function(event, data) {
            $("#message").attr('disabled', 'disabled');
        });

        $(document).on('SOCKET_ERROR', function(event, data) {
            console.log("Error");

            core_chat.onClose(data);

            alert(data.data);
        });

        core_chat.timeLoop = setInterval('core_chat.updateMessageTimes()', 1000);
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
                "<span class='avatar user-" + message['users_id'] + " " + app.users[message['users_id']]['chat_status'].toLowerCase() + "'></span>"
                + message['message'] + "" +
                "<div class='info'>" +
                    "<span class='user user-" + message['users_id'] + "'>" + app.users[message['users_id']].username + "</span>" +
                    "<span class='message-date'>" + time + "</span>" +
                "</div>" +
            "</li>");

        core_chat.scrollMessages();

        plugin_notifications.notify('LAN: New Message', message['message']);
    },

    addUser: function(user)
    {
        var elementId = core_chat.getUserElementId(user);

        //Only append if it does not already exist
        if ($('#' + elementId).length != 0) {
            return;
        }

        //Don't display the system user.
        if (user['username'] == 'system') {
            return;
        }

        var html = "<li id='" + elementId + "'>" +
                       "<ul>" +
                            "<li>" +
                                "<span class='avatar user-" + user['id'] + " " + user['chat_status'].toLowerCase() + "'></span> " +
                                "<span class='user-name'><a href='" + app.baseURL + 'users/' +user['id'] + "'>" + user['username'] + "</a></span>" +
                            "</li>" +
                        "</ul>" +
                   "</li>";

        $('#user-list').append(html);

        $('#' + elementId).addClass('them');
    },

    updateUser: function(user)
    {
        var elementId = core_chat.getUserElementId(user);

        $('#' + elementId + " .user-name").html(user['name']);

        $('.user-' + user['id']).html(user['name']);

        //Update the client user if we need to.
        if (user['id'] == app.user['id']) {
            app.user = user;
            $('#edit-profile-link').html(app.user['name']);
        }
    },

    getUserElementId: function(user) {
        return 'User-Record-' + user['id'];
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
        for (id in core_chat.messages){

            var time = moment(core_chat.messages[id]['date_created']).fromNow()

            $('#message-' + id + " .message-date").html(time);
        }

    },

    scrollMessages:function () {
        if (core_chat.messageListAutoScroll) {
            $("#message-list").scrollTop($("#message-list").prop('scrollHeight'));
        }
    }
};

$(document).on('REGISTER_PLUGINS', function(event, data) {
    core_chat.init();
});