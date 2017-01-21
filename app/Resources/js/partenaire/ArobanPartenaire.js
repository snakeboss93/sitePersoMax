/* exported ArobanPartenaire */
var ArobanPartenaire = (function () {
    'use strict';
    var module = {};

    /**
     * Permet d'ajouter le partenaire creer a la volet dans le select2.
     *
     * @param partenaireId
     * @param partenaireLibelle
     * @param partenaireSearchString
     * @param partenaireExtraInfos
     */
    module.addOptionPartenaire = function (partenaireId, partenaireLibelle, partenaireSearchString, partenaireExtraInfos) {
        var newOption = $('<option/>', {
            value: partenaireId,
            text: partenaireLibelle
        });
        newOption.attr('data-user-name', partenaireLibelle);
        newOption.attr('data-search-string', partenaireSearchString);
        newOption.attr('data-extra-infos', partenaireExtraInfos);
        $('.partenaireSelect2').append(newOption);
        $(".partenaireSelect2 option[value='" + partenaireId + "']").prop('selected', true);
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

        $('#flashbag-partenaire').empty();
        $('#flashbag-partenaire').html(ArobanAjax.prepareAlert(alert));
    };

    /**
     * Permet la persistance du partenaire creer a la volet.
     */
    module.createPartenaire = function () {
        $.post({
            url: Routing.generate('partenaire.creer.inline'),
            data: $('#form-partenaire-creer').serialize(),
            statusCode: {
                200: function (response) {
                    module.showFlashBag(response.flashbag);
                    module.addOptionPartenaire(
                        response.partenaireId,
                        response.partenaireLibelle,
                        response.partenaireSearchString,
                        response.partenaireExtraInfos
                    );
                    $('#modalAddPartenaire').modal('hide');
                },
                400: function (response) {
                    $('#modalAddPartenaire .modal-body').empty();
                    $('#modalAddPartenaire .modal-body').html(response.responseText);
                }
            }
        });
    };

    return module;
})();

