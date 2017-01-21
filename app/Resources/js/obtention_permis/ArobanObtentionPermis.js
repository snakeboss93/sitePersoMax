/* exported ArobanObtentionPermis */
var ArobanObtentionPermis = (function () {
    'use strict';
    var module = {};

    var selectorDateDemandePiece = $('div[id$="dateDemandePieces"]');
    var selectorDatePermis = $('div[id$="datePermis"]');
    var selectorAvis = $('div[id$="avis"]');
    var fieldNote = $('div[id$="note"]');
    var idFieldDemandePieces = $('input[type="checkbox"][name$="_autorisation[demandePieces]"]');

    /**
     * Fait apparaitre les date demande de pièces si il est selectionné.
     */
    module.toggleDemandePiece = function () {
        var checkboxDemandePieces = $(idFieldDemandePieces).is(':checked');

        if (checkboxDemandePieces) {
            selectorDateDemandePiece.show();
            selectorDateDemandePiece.find('label').addClass('required');
            selectorDateDemandePiece.find('select').attr('required', 'required');
            selectorAvis.find('label').removeClass('required');
            selectorAvis.find('select').removeAttr('required');
        } else {
            selectorDateDemandePiece.hide();
            selectorDateDemandePiece.find('label').removeClass('required');
            selectorDateDemandePiece.find('select').removeAttr('required');
            selectorDateDemandePiece.find('select').val('').trigger('change');
            selectorAvis.find('label').addClass('required');
            selectorAvis.find('select').attr('required', 'required');
        }
    };

    /**
     * Au chargement de la page demande autoriser.
     */
    module.onPageLoadDemandeAutoriser = function() {
        module.toggleDemandePiece();

        $(idFieldDemandePieces).on('change', function() {
            module.toggleDemandePiece();
        });
    };

    return module;
})();
