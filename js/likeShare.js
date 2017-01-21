var likeShare = (function () {
    'use strict';
    var module = {};

    /**
     * initialisation
     */
    module.init = function () {
        addLike();
        addShare();
    };

    /**
     * écoute du click sur un like
     */
    function addLike() {
        $('.like').each(function () {
            $(this).click(function () {
                var elt = $(this);
                var id = $(this)[0].id;
                id = id.split("-");
                id = id[1];
                $.ajax({
                    dataType: 'json',
                    url: '?action=addLike&id=' + id,
                    success: function (reponse) {
                        elt.html(reponse.data.like + ' <i class="fa fa-heart" aria-hidden="true"></i>');
                    }
                });
            });
        });
    }

    /**
     * ecoute du click sur un share
     */
    function addShare() {
        $('.partage').each(function () {
            $(this).click(function () {
                var id = $(this)[0].id;
                var idUser = $('#menu-user-id').html();
                id = id.split("-");
                id = id[1];
                $.ajax({
                    dataType: 'json',
                    url: '?action=addShare&id=' + id + '&idUser=' + idUser,
                    success: function (reponse) {
                        var notif = document.createElement('div');
                        notif.className = 'la-notification flow-text';
                        notif.role = 'alert';
                        notif.innerHTML = reponse.notification;
                        $('#notification').html(notif);
                        setTimeout(tools.hideNotif, 5000);
                    }
                });
            });
        });
    }

    return module;
})();
