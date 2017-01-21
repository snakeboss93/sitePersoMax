/* exported ArobanContact */
var ArobanContact = (function () {
    'use strict';
    var module = {};

    /**
     * Fait apparaitre le contact double si la checkbox est cochée.
     */
    module.toggleDoublePart = function () {
        if ($('.contact-double').is(':checked')) {
            $('.double-part').show();
            $('.single-part').removeClass('single');
        } else {
            $('.double-part').hide();
            $('.single-part').addClass('single');
        }
    };

    /**
     * Vérifie que le contact est un doublon.
     *
     * @returns {boolean}
     */
    module.checkIsDoublon = function () {
        var isDoublon = false;
        $.get({
            url: Routing.generate('contact.checkDoublon'),
            data: {
                'nom': $('#contact_nom').val(),
                'prenom': $('#contact_prenom').val(),
                'email': $('#contact_email').val(),
                'telephone_country': $('#contact_numeroTelephone_country').val(),
                'telephone': $("#contact_numeroTelephone_number").val(),
                'ville': $("#contact_adresse_ville").val(),
                'cpLibre': $('#contact_adresse_cpLibre').val(),
                'villeLibre': $('#contact_adresse_villeLibre').val()
            },
            success: function (msg) {
                isDoublon = (msg === '1');
            },
            async: false
        });
        return isDoublon;
    };

    /**
     * Permet d'ajouter le contact créé à la volée dans le select2.
     *
     * @param contactId
     * @param contactLibelle
     * @param contactSearchString
     * @param contactExtraInfos
     */
    module.addOptionContact = function (contactId, contactLibelle, contactSearchString, contactExtraInfos) {
        var newOption = $('<option/>', {
            value: contactId,
            text: contactLibelle
        });
        newOption.attr('data-user-name', contactLibelle);
        newOption.attr('data-search-string', contactSearchString);
        newOption.attr('data-extra-infos', contactExtraInfos);
        newOption.attr('selected', 'selected');
        $(".wantSelect2Contact").append(newOption);

    };

    /**
     * Réalise un message flash.
     *
     * @param flashbag
     */
    module.showFlashBag = function (flashbag) {
        var alert = {};
        var flashbagHtml;

        alert.type = flashbag.level;
        alert.message = flashbag.message;
        flashbagHtml = ArobanAjax.prepareAlert(alert);

        $('#flashbag').empty();
        $('#flashbag').html(flashbagHtml);
    };

    /**
     * Permet la persistance du contact créé à la volée.
     */
    module.createContact = function () {
        $.post({
            url: Routing.generate('contact.creer.inline'),
            data: $('#form-contact-creer').serialize(),
            statusCode: {
                200: function (response) {
                    module.showFlashBag(response.flashbag);
                    module.addOptionContact(
                        response.contactId,
                        response.contactLibelle,
                        response.contactSearchString,
                        response.contactExtraInfos
                    );
                    if (response.flashbag.level === 'success') {
                        $('#modalAddContact').modal('hide');
                    }
                    ArobanSelect2.addToSelection(
                        'input[id$=_dossier_contacts]',
                        $('input[id$=_dossier_contacts]').val(),
                        response.contactId,
                        true
                    );
                },
                400: function (response) {
                    $('#modalAddContact .modal-body').empty();
                    $('#modalAddContact .modal-body').html(response.responseText);
                    module.toggleDoublePart();
                    $('.contact-double').click(module.toggleDoublePart);
                }
            }
        });
    };

    /**
     * Masque les boutons par défaut.
     */
    module.hideModalButtons = function() {
        $('#modalAddContact').find('.modal-footer button').attr('disabled', 'disabled');
    };

    /**
     * Affiche les boutons de la modale.
     */
    module.showModalButtons = function() {
        $('#modalAddContact').find('.modal-footer button').removeAttr('disabled');
    };

    /**
     * Vérifie la validité du formulaire et affiche les boutons pour permettre la validation.
     */
    module.checkForm = function() {
        var formNom = $('#contact_nom').val();
        var formEmail = $('#contact_email').val();
        var formTelephone = $('#contact_numeroTelephone_number').val();
        var formVille = $('#ville').val();

        if (formNom !== '' && formVille !== '' && formEmail+formTelephone !== '') {
            $('#modalAddContactAlert').hide();
            module.showModalButtons();
        } else {
            $('#modalAddContactAlert').show();
            module.hideModalButtons();
        }
    };

    /**
     * Au chargement de la page de création de contact.
     */
    module.onPageLoadCreer = function () {
        $('.double-part').hide();

        $('.contact-double').click(module.toggleDoublePart());

        $('#copyButton').click(function (event) {
            event.preventDefault();
            $('#contact_sousContact_nom').val($('#contact_nom').val());
        });

        $('#submitProxy').click(function (event) {
            event.preventDefault();
        });

        $('#modalConfirmationDoublonOK').click(function () {
            $('#submitContactModal').click();
        });

        $('#submitProxy').click(function (e) {
            e.preventDefault();
            $.get(
                Routing.generate('contact.checkDoublon'),
                {
                    'nom': $('#contact_nom').val(),
                    'prenom': $('#contact_prenom').val(),
                    'email': $('#contact_email').val(),
                    'telephone_country': $('#contact_numeroTelephone_country:selected').val(),
                    'telephone': $('#contact_numeroTelephone').val()
                },
                function (msg) {
                    if (msg === '1') {
                        $('#modalConfirmationDoublon').modal('show');
                        return false;
                    } else {
                        $('#submitContactModal').click();
                    }
                }
            );
        });
    };

    /**
     * @param buttonSelector
     * @param contactId
     */
    module.submitWithButton = function (buttonSelector, contactId) {
        $.get(
            Routing.generate('contact.checkDoublon'),
            {
                'nom': $('#contact_nom').val(),
                'prenom': $('#contact_prenom').val(),
                'email': $('#contact_email').val(),
                'telephone_country': $('#contact_numeroTelephone_country:selected').val(),
                'telephone': $('#contact_numeroTelephone').val(),
                'id': contactId
            },
            function (msg) {
                if (msg === '1') {
                    $('#modalConfirmationDoublon').modal('show');
                    return false;
                } else {
                    $(buttonSelector).click();
                }
            }
        );
    };

    /**
     * Au chargement de la page d'édition de contact.
     */
    module.onPageLoadEdit = function (contactId) {

        module.toggleDoublePart();

        $('.contact-double').click(function () {
            module.toggleDoublePart();
        });
        $('#copyButton').click(function (event) {
            event.preventDefault();
            $('#contact_sousContact_nom').val($('#contact_nom').val());
        });

        $('#modalConfirmationDoublonOK').click(function () {
            $('#submit').click();
        });

        $('#submitProxy').click(function (e) {
            e.preventDefault();
            module.submitWithButton('#submit', contactId);
        });

        $('#updateAndDetach').click(function (e) {
            e.preventDefault();
            module.submitWithButton('#submitDetach', contactId);
        });
    };

    /**
     * Récupère un contact et l'injecte dans le champ adresse complète lors de la création d'un courrier.
     */
    module.onPageLoadCourrierCreer = function(dossierContacts) {
        $('.contactSelect2').on('select2:select', function () {
            var contactId = $(this).val();

            $.get(
                Routing.generate('contact.recuperer', {id: contactId}),
                function( data ) {
                    var cedex = '';
                    var adresseComplete = dossierContacts+"\n";

                    if (null !== data.cedex) {
                        cedex = ' '+data.cedex+' '+data.suffixeCedex+"\n";
                    }

                    adresseComplete += data.adresse+"\n";

                    if (null !== data.ville) {
                        adresseComplete += data.ville.cp+' '+data.ville.nom+cedex+"\n";
                    }

                    if (null !== data.pays) {
                        adresseComplete += data.pays.libelle+"\n";
                    }

                    $('#courrier_courrierDestinataire_adresseComplete').val(adresseComplete);
                }
            );
        });
    };

    return module;
})();

