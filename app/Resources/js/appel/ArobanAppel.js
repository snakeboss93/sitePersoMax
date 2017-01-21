/* exported ArobanAppel */
var ArobanAppel = (function () {
    'use strict';
    var module = {};

    /**
     * Méthode qui affiche la génération d'alerte (?)
     */
    module.toggleGenerationAlerte = function () {
        var state = $('#appel_sensAppel_0').is(':checked');
        $('#appel_genererUneAlerte').closest('.form-group').toggle(state);
        $('#appel_roleAlerte').closest('.form-group').toggle(state);
    };

    /**
     * Méthode pour la suppression d'un appel.
     */
    module.suppressionAppel = function () {
        $('.deleteAppelLink').click(function (event) {
            if (!confirm('Vous êtes sur le point de supprimer un appel, continuer?')) {
                event.stopPropagation();
                event.preventDefault();
                return;
            }
            $(this).closest('li').children('form').submit();
        });
    };

    return module;
})();

