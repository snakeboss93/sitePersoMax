/* exported ArobanEtudeDeProjet */
var ArobanEtudeDeProjet = (function () {
    'use strict';
    var module = {};

    var selectorPrestataires = $('div[id$="prestataires"]');
    var selectorConcepteurs = $('div[id$="concepteur"]');
    var idFieldPrestataire = $('input[type="checkbox"][name$="_planifier[prestataire]"]');

    /**
     * Fait apparaitre les prestataires si il est selectionné.
     */
    module.togglePrestataire = function () {
        var selecteurPrestataire = $(idFieldPrestataire).is(':checked');

        if (selecteurPrestataire) {
            selectorPrestataires.show();
            selectorPrestataires.find('label').addClass('required');
            selectorPrestataires.find('select').attr('required', 'required');
            selectorConcepteurs.hide();
            selectorConcepteurs.find('label').removeClass('required');
            selectorConcepteurs.find('select').removeAttr('required');
            selectorConcepteurs.find('select').val('').trigger('change');
        } else {
            selectorPrestataires.hide();
            selectorPrestataires.find('label').removeClass('required');
            selectorPrestataires.find('select').removeAttr('required');
            selectorPrestataires.find('select').val('').trigger('change');
            selectorConcepteurs.show();
            selectorConcepteurs.find('label').addClass('required');
            selectorConcepteurs.find('select').attr('required', 'required');
        }
    };

    /**
     * Au chargement de la page demande attribuer.
     */
    module.onPageLoadDemandeAttribuer = function() {
        module.togglePrestataire();

        $(idFieldPrestataire).on('change', function() {
            module.togglePrestataire();
        });
    };

    return module;
})();
