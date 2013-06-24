var plugin_steam = {

    init: function ()
    {
        $(document).on('STEAM_USER_INFO', function(event, data) {
            for (var i = 0; i < data.length; i++) {
                var info = data[i];

                //Make sure we have a steam li for the user.
                if ($("#User-Record-" + info.users_id + " ul .steam").length == 0) {
                    $("#User-Record-" + info.users_id + " ul").append("<li class='steam'></li>");
                }

                var status = "";

                status += plugin_steam.personastateToString(info.personastate);

                var playing = "";
                if (info.gameextrainfo) {
                    playing += " - Playing: <a href='steam://run/" + info.gameid + "'>" + info.gameextrainfo + "</a>";
                }


                $("#User-Record-" + info.users_id + " ul .steam").html(
                    "<span class='label'>" +
                        "<strong>Steam:</strong>" + " <a href='steam://url/SteamIDPage/" + info.steamid + "'>" + info.personaname  + "</a> " +
                        status + playing +
                    "</span>"
                );

                $("#steam-info-" + info.users_id).append();

            }
        });
    },

    personastateToString: function(state)
    {
        var status = "";
        switch (state) {
            case 0:
                status += "Offline";
                break;
            case 1:
                status += "Online";
                break;
            case 2:
                status += "Busy";
                break;
            case 3:
                status += "Away";
                break;
            case 4:
                status += "Snooze";
                break;
            case 5:
                status += "Looking to Trade";
                break;
            case 6:
                status += "Looking to Play";
                break;
        }

        return status;
    }
}

$(document).on('REGISTER_PLUGINS', function(event, data) {
    plugin_steam.init();
});