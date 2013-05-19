var app = {
    user       : false,
    users      : [],
    baseURL    : '',
    connection : false,

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

        $(document).on('SOCKET_OPEN', function(event, data) {

        });

        $(document).on('SOCKET_CLOSE', function(event, data) {
            //Don't diplay a warning if there is not a user logged in yet.
            if (app.user == false) {
                return;
            }

            app.displayPageMessage('error', 'Connection Closed', 'The connection to the server has been closed.');
        });

        $(document).on('SOCKET_ERROR', function(event, data) {
            app.displayPageMessage('error', 'Connection Error', 'The connection to the server encountered an error.');
        });

        $(document).on('SOCKET_MESSAGE', function(event, data) {
            data = JSON.parse(data.data);

            if (data['action'] == undefined) {
                console.log('Error: No action provided');
                return;
            }
            console.log(data['action']);
            $(document).trigger(data['action'], data['data']);
        });

        $(document).on('USER_INFORMATION', function(event, data) {
            app.user = data['Chat\\User\\User'];
        });

        $(document).on('USER_CONNECTED', function(event, data) {
            //Add the user to our internal users array.
            app.users[data['Chat\\User\\User']['id']] = data['Chat\\User\\User'];
        });

        $(document).trigger('REGISTER_PLUGINS');
    },

    displayPageMessage : function(type, title, contents) {
        $('#message-container').append("<div class='alert alert-" + type + "'>"+
            "<button type='button' class='close' data-dismiss='alert'>&times;</button>"+
            "<h4>" + title + "</h4>" + contents + "</div>");
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
    }
};