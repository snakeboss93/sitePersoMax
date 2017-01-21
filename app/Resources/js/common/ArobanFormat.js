/**
 * Traite les retours d'appel ajax pour afficher des messages d'alerte
 */
/* exported ArobanFormat */
var ArobanFormat = (function () {
    'use strict';
    var module = {};
    var locale = 'fr-FR';

    /**
     * @param value
     */
    module.number = function (value) {
        return new Intl.NumberFormat(locale).format(value);
    };

    /**
     * @param value
     */
    module.money = function (value) {
        return new Intl.NumberFormat(locale).format(value) + ' \u20AC';
    };

    return module;
})();

