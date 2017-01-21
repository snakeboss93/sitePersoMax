/**
 * Traite les retours d'appel ajax pour afficher des messages d'alerte
 */
/* exported ArobanAjax */
var ArobanAjax = (function () {
    'use strict';
    var module = {};

    /**
     * @param data
     */
    module.prepareAlert = function (data) {
        return '<div class="messages"><div class="alert alert-dismissible alert-' + data.type + '" role="alert">'
            + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            + '<span aria-hidden="true">&times;</span>'
            + '</button>'
            + data.message
            + '</div></div>';
    };

    /**
     * @param data
     */
    module.alert = function (data) {
        var alert = module.prepareAlert(data);
        $('#main-content').find('.container-fluid:first').prepend(alert);
    };

    return module;
})();

