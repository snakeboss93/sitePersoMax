/* exported ArobanDossierOrigine */
var ArobanDossierOrigine = (function () {
    'use strict';
    var module = {};

    var selectorOrigineGroupe = 'select[id$="_dossier_origine_origine_groupe"]';
    var selectorOrigineIntitule = 'select[id$="_dossier_origine_origine"]';
    var selectorChampLibre = 'input[id$="_dossier_detailOrigine"]';
    var champLibreGroups = [];

    /**
     * @param value
     */
    module.setChampLibreGroups = function (value) {
        champLibreGroups = value;
    };

    /**
     * Réinitialise le sous champ origine.
     */
    module.resetSelectIntitule = function () {
        $(selectorOrigineIntitule + ' option:selected').removeAttr('selected');
    };

    /**
     * Massque le champ origine intitulé lorsque l'on sélectionne aucune origine.
     */
    module.hideFieldDetail = function () {
      $(selectorOrigineIntitule).parent().parent().hide();
    };

    /**
     * Affiche les valeurs du sous champ origine.
     *
     * @param groupe
     */
    module.showOptionsIntitule = function (groupe) {
        var selectorGroupe = 'option[data-groupe="' + groupe + '"';
        var numberOptions = $(selectorOrigineIntitule + ' ' + selectorGroupe + ']').length;
        $(selectorOrigineIntitule + ' option').not(':first').hide();
        $(selectorGroupe).show();

        // Si qu'un seul élément ( = 2 options ) au niveau 2 alors on sélect l'élément directement
        if (numberOptions === 1) {
            $(selectorOrigineIntitule).val($(selectorOrigineIntitule + ' ' + selectorGroupe + ']').eq(0).val());
            $(selectorOrigineIntitule).parent().parent().hide();
        } else {
            $(selectorOrigineIntitule).parent().parent().show();
        }
    };

    /**
     * Affiche un champ libre pour les groupes définis.
     *
     * @param groupe
     */
    module.triggerChampLibre = function (groupe) {
        // Pour ne pas réinitialiser un champ qui contiendrait une valeur !
        var currentValueChampLibre = $(selectorChampLibre).val();
        if (champLibreGroups.indexOf(groupe) !== -1) {
            $(selectorChampLibre).val(currentValueChampLibre);
            $(selectorChampLibre).parent().parent().show();
        } else {
            if (groupe !== null) {
                $(selectorChampLibre).val('');
            }
            $(selectorChampLibre).parent().parent().hide();
        }
    };

    /**
     * Affiche et masque les champs selon l'origine sélectionné.
     *
     * @param groupe
     */
    module.onChange = function(groupe) {
        module.resetSelectIntitule();
        module.showOptionsIntitule(groupe);
        module.triggerChampLibre(groupe);
        if (groupe === undefined) {
            module.hideFieldDetail();
        }
    };

    /**
     * Au chargement de la page de création.
     */
    module.onPageLoadCreer = function () {
        $(selectorOrigineGroupe + ', ' + selectorOrigineIntitule).parent().parent().find('label').addClass('sr-only');
        $(selectorOrigineIntitule).parent().parent().hide();
        module.triggerChampLibre(null);

        $('select[id$="_dossier_origine_origine_groupe"]').on('change', function () {
            var groupe = $(this).find(':selected').attr('data-groupe');
            module.onChange(groupe);
        });
    };

    /**
     * Au chargement de la page de modification.
     */
    module.onPageLoadModifier = function () {
        var groupeCurrent = $(selectorOrigineGroupe).find(':selected').attr('data-groupe');
        $(selectorOrigineGroupe + ', ' + selectorOrigineIntitule).parent().parent().find('label').addClass('sr-only');
        $(selectorOrigineIntitule).parent().parent().hide();
        module.showOptionsIntitule(groupeCurrent);
        module.triggerChampLibre(null);

        $('select[id$="_dossier_origine_origine_groupe"]').on('change', function () {
            var groupe = $(this).find(':selected').attr('data-groupe');
            module.onChange(groupe);
        });
    };

    return module;
})();
