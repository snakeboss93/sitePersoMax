/* exported ArobanTerrainAnnonce */
var ArobanTerrainAnnonce = (function () {
    'use strict';
    var module = {};

    /**
     * Au chargement de la page de création.
     */
    module.onPageLoadCreer = function () {
        $('#annonce_diffusionAnnonce').click(function () {
            $('#affichageBouton').toggle();
        });

        $('#affichageBouton').hide();

        $('div[id^="headingannonce_"]').click(function () {
            $(this).removeClass('panel-warning');
            $(this).addClass('panel-success');
            $(this).parent().removeClass('panel-warning');
            $(this).parent().addClass('panel-success');
            if (!$('div[id="accordion"]').children().children().hasClass('panel-warning')) {
                $('#affichageBouton').show();
            }
        });
    };

    /**
     * Au chargement de la page d'édition.
     */
    module.onPageLoadModifier = function () {
        $('div[id^="headingannonce_"]').each(function () {
            $(this).removeClass('panel-warning');
            $(this).addClass('panel-success');
            $(this).parent().removeClass('panel-warning');
            $(this).parent().addClass('panel-success');
        });
    };

    return module;
})();

