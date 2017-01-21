var connection = (function () {
    'use strict';
    var module = {};

    module.menuOut = "<div id='menuOut'><ul>"
        + "<li><a href='?action=index'><i class='fa fa-home' aria-hidden='true'></i>Accueil</a></li>"
        + "<li><a href='?action=login'><i class='fa fa-sign-in' aria-hidden='true'></i>Se Connecter</a></li>"
        + "<li><a href='?action=mentionsLegales'><i class='fa fa-balance-scale' aria-hidden='true'></i>Mentions legales</a></li>"
        + "<li><a href='?action=contact'><i class='fa fa-pencil' aria-hidden='true'></i>Contact</a></li>"
        + "</ul></div>";

    /**
     * Initialisation pour la déconnection.
     */
    module.init = function () {
        $('#deconnection').click(function () {
            $.ajax({
                dataType: 'json',
                url: '?action=logoutAjax',
                success: function (reponse) {
                    $('#notification').html(reponse.notification);
                    $('#notification').addClass('la-notification');
                    $('#menu').html(module.menuOut);
                }
            });
        });
    };

    return module;
})();
