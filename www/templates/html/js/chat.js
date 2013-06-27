var core_chat = {
    messages                   : [],
    timeLoop                   : false,
    messageListAutoScroll      : true, //auto scroll the message list
    messageListLatestMessageID : 0,
    latestTimeRequested        : 0,

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
            core_chat.addUser(data);
        });

        $(document).on('USER_DISCONNECTED', function(event, data) {
            app.setUserStatus(data);
        });

        $(document).on('USER_INFORMATION', function(event, data) {
            var elementId = core_chat.getUserElementId(app.user);

            $('#' + elementId).removeClass('them');
            $('#' + elementId).addClass('me');
        });

        $(document).on('USER_UPDATED', function(event, data) {
            core_chat.updateUser(data);

            //Update the internal user,
            app.users[data['id']] = data;
        });

        $(document).on('MESSAGE_NEW', function(event, data) {
            core_chat.addMessage(data);
        });

        $(document).on('SOCKET_OPEN', function(event, data) {
            $("#message").removeAttr('disabled');

            //Check if we need to retrieve messages.
            if (core_chat.messages.length == 0) {
                app.send('GET_CHAT_MESSAGES', {latest_message_id: 0});
            }
        });

        $(document).on('SOCKET_CLOSE', function(event, data) {
            $("#message").attr('disabled', 'disabled');
        });

        $(document).on('SOCKET_ERROR', function(event, data) {
            console.log("Error");

            core_chat.onClose(data);
        });

        $(' #load-more-messages').click(function(){
            var oldest_id = core_chat.messageListLatestMessageID;

            //Go backwards from the most recent id to look where this message should be placed.
            for (var i = core_chat.messageListLatestMessageID; i >= 0; i--) {
                if (core_chat.messages[i] !== undefined) {
                    oldest_id = i;
                }
            }

            if (oldest_id == 1) {
                return;
            }

            app.send('GET_CHAT_MESSAGES', {before_message_id: oldest_id});
        });

        core_chat.timeLoop = setInterval('core_chat.updateMessageTimes()', 1000);
    },

    addMessage: function(message)
    {
        //Skip if it already exists
        if (this.messages[message.id] !== undefined) {
            return;
        }

        var userClass = 'them';

        if (message['users_id'] == app.user['id']) {
            userClass = 'me';
        }

        var time = moment(message['date_created']).fromNow();

        html = "<li id='message-" + message['id'] + "' class='" + userClass + "'>" +
            "<span class='avatar user-" + message['users_id'] + " " + app.users[message['users_id']]['chat_status'].toLowerCase() + "'></span>"
            + message['message'] + "" +
            "<div class='info'>" +
            "<span class='user user-" + message['users_id'] + "'>" + app.users[message['users_id']].username + "</span>" +
            " - <span class='message-date'>" + time + "</span>" +
            "</div>" +
            "</li>";

        if (parseInt(message.id) > core_chat.messageListLatestMessageID) {
            $("#message-list").append(html);
        } else {
            var closest_id = core_chat.messageListLatestMessageID;

            //Go backwards from the most recent id to look where this message should be placed.
            for (var i = core_chat.messageListLatestMessageID; i > parseInt(message.id); i--) {
                if (core_chat.messages[i] !== undefined) {
                    closest_id = i;
                }
            }

            $("#message-" + closest_id).before(html);
       }

        //Add the message to the internal list of messages.
        core_chat.messages[parseInt(message['id'])] = message;

        $(document).trigger('MESSAGE_ADDED', [message]);

        if (message.id > core_chat.messageListLatestMessageID) {
            core_chat.messageListLatestMessageID = message.id;
            core_chat.latestTimeRequested = message.time_requested;
            core_chat.scrollMessages();
            $(document).trigger('NEW_MESSAGE_ADDED', [message]);
        }

        if (message.time_requested == core_chat.latestTimeRequested) {
            core_chat.scrollMessages();
        }
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
                       "<span class='avatar user-" + user['id'] + " " + user['chat_status'].toLowerCase() + "'></span> " +
                       "<ul>" +
                            "<li>" +
                                "<span class='user-name'><a href='" + app.baseURL + 'users/' +user['id'] + "'>" + user['username'] + "</a></span>" +
                            "</li>" +
                        "</ul>" +
                        "<div style='clear:both'></div>" +
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