/**
 * Traite les retours d'appel ajax pour afficher des messages d'alerte
 */
/* exported ArobanTerrain */
var ArobanTerrain = (function () {
    'use strict';
    var module = {};
    var emplacementTerrainTypeLotissement;
    var interlocuteurTypeProprietaire;
    var interlocuteurTypePartenaire;
    var interlocuteurTypeContact;

    /**
     * Setter EmplacementTerrainType Lotissement
     * @param value
     */
    module.setEmplacementTerrainTypeLotissement = function (value) {
        emplacementTerrainTypeLotissement = value;
    };

    /**
     * Setter InterlocuteurType Proprietaire
     * @param value
     */
    module.setInterlocuteurTypeProprietaire = function (value) {
        interlocuteurTypeProprietaire = value;
    };

    /**
     * SetterInterlocuteurType Partenaire
     * @param value
     */
    module.setInterlocuteurTypePartenaire = function (value) {
        interlocuteurTypePartenaire = value;
    };

    /**
     * SetterInterlocuteurType Contact
     * @param value
     */
    module.setInterlocuteurTypeContact = function (value) {
        interlocuteurTypeContact = value;
    };

    /**
     * Fait aparaitre lotissement.
     */
    module.toggleLots = function () {
        var state = $('select[id$=_emplacement]').val() === emplacementTerrainTypeLotissement;
        $('input[id$=_nombreLot]').closest('.form-group').toggle(state);
        $('input[id$=_numeroLot]').closest('.form-group').toggle(state);
    };

    /**
     * Fait aparaitre contact.
     */
    module.toggleContact = function () {
        var interlocuteurValue = $('select[id$=_interlocuteur]').val();
        var flagProprietaire = interlocuteurValue === interlocuteurTypeProprietaire;
        var flagPartenaire = interlocuteurValue === interlocuteurTypePartenaire;
        var flagContactClient = interlocuteurValue === interlocuteurTypeContact;
        var terrainProprietaire = $('#proprietaire');
        var terrainPartenaire = $('select[id$=_partenaire]');
        var terrainContactClient = $('#contact_client');

        terrainProprietaire.closest('.form-group').toggle(flagProprietaire);
        terrainProprietaire.attr('required', flagProprietaire);
        terrainPartenaire.closest('.form-group').toggle(flagPartenaire);
        terrainPartenaire.attr('required', flagPartenaire);
        terrainContactClient.closest('.form-group').toggle(flagContactClient);
        terrainContactClient.attr('required', flagContactClient);
    };

    return module;
})();

