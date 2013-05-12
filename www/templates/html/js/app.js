var app = {
    user       : false,
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

        $(document).on('USER_INFORMATION', function(event, data) {
            app.user = data['Chat\\User\\User'];
        });

        $(document).trigger('REGISTER_PLUGINS');
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
};