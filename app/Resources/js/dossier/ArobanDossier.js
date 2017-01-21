/* exported ArobanDossier */
var ArobanDossier = (function () {
    'use strict';
    var module = {};

    var selectorRecherche = $('div[id$="etatRecherche"]');
    var fieldSecteur = $('fieldset[class="secteur"]');

    var dossierOperationAutre;
    var dossierRecherche;

    var idDossier;

    var dossierOrigine;
    var selectorDossierOrigineCurrent;
    var currentGroupe;
    var selectorCurrentGroupe;
    var selectorCurrentGroupeSelect;
    var currentGroupeValue;
    var dossierPotentiel;
    var budgetMaison;
    var budgetTerrain;

    var utilisateurAttribution;
    var hasRoleCommercial;
    var contactSelected;
    var villeTerrain;

    /**
     * Setter idDossier
     * @param value
     */
    module.setIdDossier = function (value) {
        idDossier = value;
    };

    /**
     * Getter idDossier
     */
    module.getIdDossier = function () {
        return idDossier;
    };

    /**
     * Setter utilisateurAttribution
     * @param value
     */
    module.setUtilisateurAttribution = function (value) {
        utilisateurAttribution = value;
    };

    /**
     * Setter hasRoleCommercial
     * @param value
     */
    module.setHasRoleCommercial = function (value) {
        hasRoleCommercial = value;
    };

    /**
     * Setter contactSelected
     * @param value
     */
    module.setContactSelected = function (value) {
        contactSelected = value;
    };

    /**
     * Setter villeTerrain
     * @param value
     */
    module.setVilleTerrain = function (value) {
        villeTerrain = value;
    };

    /**
     * Getter utilisateurAttribution
     */
    module.getUtilisateurAttribution = function () {
        return utilisateurAttribution;
    };

    /**
     * Getter hasRoleCommercial
     */
    module.getHasRoleCommercial = function () {
        return hasRoleCommercial;
    };

    /**
     * Getter contactSelected
     */
    module.getContactSelected = function () {
        return contactSelected;
    };

    /**
     * Getter villeTerrain
     */
    module.getVilleTerrain = function () {
        return villeTerrain;
    };

    /**
     * Setter dossierOperationAutre
     * @param value
     */
    module.setDossierOperationAutre = function (value) {
        dossierOperationAutre = value;
    };

    /**
     * Setter dossierRecherche
     * @param value
     */
    module.setDossierRecherche = function (value) {
        dossierRecherche = value;
    };

    /**
     * Setter dossierOrigine
     * @param value
     */
    module.setDossierOrigine = function (value) {
        dossierOrigine = value;
    };

    /**
     * Setter selectorDossierOrigineCurrent
     * @param value
     */
    module.setSelectorDossierOrigineCurrent = function (value) {
        selectorDossierOrigineCurrent = value;
    };

    /**
     * Setter currentGroupe
     * @param value
     */
    module.setCurrentGroupe = function (value) {
        currentGroupe = value;
    };

    /**
     * Setter selectorCurrentGroupe
     * @param value
     */
    module.setSelectorCurrentGroupe = function (value) {
        selectorCurrentGroupe = value;
    };

    /**
     * Setter selectorCurrentGroupeSelect
     * @param value
     */
    module.setSelectorCurrentGroupeSelect = function (value) {
        selectorCurrentGroupeSelect = value;
    };

    /**
     * Setter currentGroupeValue
     * @param value
     */
    module.setCurrentGroupeValue = function (value) {
        currentGroupeValue = value;
    };

    /**
     * Setter dossierPotentiel
     * @param value
     */
    module.setDossierPotentiel = function (value) {
        dossierPotentiel = value;
    };

    /**
     * Setter dossierOperationAutre
     * @param value
     */
    module.setBudgetMaison = function (value) {
        budgetMaison = value;
        if ('' === value) {
            budgetMaison = 0;
        }
    };

    /**
     * Setter budgetTerrain
     * @param value
     */
    module.setBudgetTerrain = function (value) {
        budgetTerrain = value;
        if ('' === value) {
            budgetTerrain = 0;
        }
    };

    /**
     * Initialiser les variables pour la page de modification de dossier.
     *
     * @param origine
     * @param potentiel
     */
    module.initModifier = function (origine, potentiel) {
        module.setDossierOrigine(origine);
        module.setSelectorDossierOrigineCurrent($('#modifier_dossier_origine_origine option[value="' + dossierOrigine + '"]'));
        module.setCurrentGroupe($(selectorDossierOrigineCurrent).attr('data-groupe'));
        module.setSelectorCurrentGroupe($('#modifier_dossier_origine_origine_groupe option[data-groupe="' + currentGroupe + '"]'));
        module.setSelectorCurrentGroupeSelect($(selectorCurrentGroupe).parent());
        module.setCurrentGroupeValue($(selectorCurrentGroupe).val());
        module.setDossierPotentiel(potentiel);
    };

    /**
     * Fait aparaitre les opérations si il est selectionné.
     */
    module.toggleOperation = function () {
        if (parseInt(dossierOperationAutre) === parseInt($('select[id$=_dossier_operation]').val())) {
            $('div[id$=_dossier_autre]').show();
        } else {
            $('div[id$=_dossier_autre]').hide();
        }
    };

    /**
     * Permet de modifier l'affichage de la note du potentiel en fonction de sa note au chargement de la page.
     * Cette méthode est à appeller dans chaque tpl qui inclu un rating.
     * (dans le document ready)
     *
     * @param noteDuDossier valeur de la note
     */
    module.ratingOnLoad = function (noteDuDossier) {
        if ('1' === noteDuDossier) {
            $("label[for$='potentiel_faible']").addClass('rating-checked');
        } else if ('2' === noteDuDossier) {
            $("label[for$='potentiel_faible']").addClass('rating-checked');
            $("label[for$='potentiel_moyen']").addClass('rating-checked');
        } else if ('3' === noteDuDossier) {
            $("label[for$='potentiel_faible']").addClass('rating-checked');
            $("label[for$='potentiel_moyen']").addClass('rating-checked');
            $("label[for$='potentiel_fort']").addClass('rating-checked');
        } else {
            $("label[for$='potentiel_zero']").addClass('no-rating-checked');
        }
    };

    /**
     * Permet de masquer le block autre au chargement de la page.
     * Si la valeur autre est selectionner dans les opréation alors le block aparait.
     */
    module.operationOnLoad = function () {
        $('#form_group_modifier_dossier_autre').hide();
        $('#modifier_dossier_operation').on('change', function () {
            module.toggleOperation();
        });
    };

    /**
     * Permet de masquer le block gerer par une societe.
     */
    module.masquerBlockGereSociete = function () {
        $('#gere-par-societe').hide();
    };

    /**
     * Permet d'afficher le block gerer par une societe.
     */
    module.afficherBlockGereSociete = function () {
        $('#gere-par-societe').show();
    };

    /**
     * Permet d'afficher ou de masquer le block gerer par une societe celon l'état de la checkBox associé.
     */
    module.afficherBlockGereSocieteOnChange = function () {
        $('#modifier_dossier_gereParSociete').change(function () {
            if ($(this).is(':checked')) {
                module.afficherBlockGereSociete();
            } else {
                module.masquerBlockGereSociete();
            }
        });
    };

    /**
     * Initilise l'affichage si la checkbox est cocher ou non.
     */
    module.initBlockGereSociete = function () {
        if ($('#modifier_dossier_gereParSociete').is(':checked')) {
            module.afficherBlockGereSociete();
        } else {
            module.masquerBlockGereSociete();
        }
    };

    /**
     * Calcul le prix au chargement de la page si il est renseigner, sinon 0 €
     */
    module.calculPrixOnLoad = function () {
        var total = parseInt(budgetMaison) + parseInt(budgetTerrain);
        var selectorTotal = '#budget-total';
        if (isNaN(total)) {
            if (!isNaN(budgetMaison)) {
                total = budgetMaison;
            }
            if (!isNaN(budgetTerrain)) {
                total = budgetMaison;
            }
            $(selectorTotal).text(ArobanFormat.money(0));
        } else {
            $(selectorTotal).text(ArobanFormat.money(total));
        }
    };

    /**
     * Calcul le prix au changement d'un input de la page si il est renseigner, sinon 0 €
     */
    module.calculPrixOnChange = function () {
        var budgetMaison = 0;
        var budgetTerrain = 0;
        $('#modifier_dossier_budgetMaison').change(function () {
            budgetMaison = parseInt(ArobanDossier.setBudgetMaison($('#modifier_dossier_budgetMaison').val()));
            budgetTerrain = parseInt(ArobanDossier.setBudgetTerrain($('#modifier_dossier_budgetTerrain').val()));
            module.calculPrix(budgetMaison, budgetTerrain);
        });
        $('#modifier_dossier_budgetTerrain').change(function () {
            budgetMaison = parseInt(ArobanDossier.setBudgetMaison($('#modifier_dossier_budgetMaison').val()));
            budgetTerrain = parseInt(ArobanDossier.setBudgetTerrain($('#modifier_dossier_budgetTerrain').val()));
            module.calculPrix(budgetMaison, budgetTerrain);
        });
    };

    /**
     * Calcul le prix avec des parametres.
     *
     * @param budgetMaison
     * @param budgetTerrain
     */
    module.calculPrix = function (budgetMaison, budgetTerrain) {
        var total = budgetMaison + budgetTerrain;
        if (isNaN(total)) {
            if (!isNaN(budgetMaison)) {
                total = budgetMaison;
                $('#budget-total').text(ArobanFormat.money(budgetMaison));
            } else if (!isNaN(budgetTerrain)) {
                total = budgetTerrain;
                $('#budget-total').text(ArobanFormat.money(budgetTerrain));
            } else {
                $('#budget-total').text(ArobanFormat.money(0));
            }
        } else {
            $('#budget-total').text(ArobanFormat.money(total));
        }
    };

    /**
     * Affiche les fieldset des secteurs si "Recherche" est sélectionné.
     */
    module.toggleSecteur = function () {
        $(fieldSecteur).hide();
        if (parseInt(dossierRecherche) === parseInt($('select[id$=_dossier_etatRecherche]').val())) {
            $(fieldSecteur).show();
        }

        $(selectorRecherche).change(function () {
            $(fieldSecteur).hide();
            if (parseInt(dossierRecherche) === parseInt($('select[id$=_dossier_etatRecherche]').val())) {
                $(fieldSecteur).show();
            }
        });
    };

    /**
     * Comportement au chargement de la page.
     * Défini aussi les bind.
     */
    module.onPageLoadModifier = function () {
        $(selectorCurrentGroupeSelect).val(currentGroupeValue);
        $(selectorCurrentGroupeSelect).change();
        $('#modifier_dossier_origine_origine').val(dossierOrigine);

        module.initBlockGereSociete();
        module.calculPrixOnLoad();
        module.afficherBlockGereSocieteOnChange();
        module.calculPrixOnChange();
        module.operationOnLoad();
        module.ratingOnLoad(dossierPotentiel);
        module.toggleOperation();
        module.toggleSecteur();
    };

    /**
     * Comportement au chargement de la page.
     * Défini aussi les bind.
     */
    module.onPageLoadCreer = function () {
        $('#form_group_creer_dossier_autre').hide();

        /** Appartient a la modale de creer contact **/
        ArobanContact.toggleDoublePart();

        $('#creer_dossier_operation').on('change', function () {
            ArobanDossier.toggleOperation();
        });

        $('.contact-double').click(ArobanContact.toggleDoublePart);

        $('#copyButton').click(function () {
            $('#contact_sousContact_nom').val($('#contact_nom').val());
        });

        ArobanContact.hideModalButtons();

        $('#contact_nom, #contact_email, #ville, #contact_numeroTelephone_number').on('change', function() {
            ArobanContact.checkForm();
        });

        $('#modalAddContactButton').click(function (event) {
            var continuer = true;
            var isDoublon = ArobanContact.checkIsDoublon();
            event.preventDefault();
            if (isDoublon) {
                continuer = confirm('Doublon ? Continuer ?');
            }
            if (continuer) {
                ArobanContact.createContact();
                $('#ville').val('').trigger('change');
                $('#form-contact-creer')[0].reset();
            }
        });
    };

    return module;
})();

