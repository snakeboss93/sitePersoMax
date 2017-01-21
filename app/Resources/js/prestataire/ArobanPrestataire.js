/* exported ArobanPrestataire */
var ArobanPrestataire = (function () {
    'use strict';
    var module = {};

    /**
     * Permet d'ajouter le prestataire creer a la volet dans le select2.
     *
     * @param prestataireId
     * @param prestataireLibelle
     * @param prestataireSearchString
     * @param prestataireExtraInfos
     */
    module.addOptionPrestataire = function (prestataireId, prestataireLibelle, prestataireSearchString, prestataireExtraInfos) {
        var newOption = $('<option/>', {
            value: prestataireId,
            text: prestataireLibelle
        });
        newOption.attr('data-user-name', prestataireLibelle);
        newOption.attr('data-search-string', prestataireSearchString);
        newOption.attr('data-extra-infos', prestataireExtraInfos);
        $('.prestataireSelect2').append(newOption);
        $(".prestataireSelect2 option[value='" + prestataireId + "']").prop('selected', true);
    };

    /**
     * Réalise un message flash.
     *
     * @param flashbag
     */
    module.showFlashBag = function (flashbag) {
        var alert = {};
        alert.type = flashbag.level;
        alert.message = flashbag.message;

        $('#flashbag-prestataire').empty();
        $('#flashbag-prestataire').html(ArobanAjax.prepareAlert(alert));
    };

    /**
     * Permet la persistance du prestataire creer a la volet.
     */
    module.createPrestataire = function () {
        $.post({
            url: Routing.generate('prestataire.creer.inline'),
            data: $('#form-prestataire-creer').serialize(),
            statusCode: {
                200: function (response) {
                    module.showFlashBag(response.flashbag);
                    module.addOptionPrestataire(
                        response.prestataireId,
                        response.prestataireLibelle,
                        response.prestataireSearchString,
                        response.prestataireExtraInfos
                    );
                    $('#modalAddPrestataire').modal('hide');
                },
                400: function (response) {
                    $('#modalAddPrestataire .modal-body').empty();
                    $('#modalAddPrestataire .modal-body').html(response.responseText);
                }
            }
        });
    };

    return module;
})();

