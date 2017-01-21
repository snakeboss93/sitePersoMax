/* exported ArobanSelect2 */
var ArobanSelect2 = (function () {
    'use strict';
    var module = {};

    /* eslint max-statements: ["error", 30] */
    /* Cela aurait était bien de le faire correct dès le départ, la c'est trop chaud de changer. */

    /**
     * Synchronise les données au champ caché associé au select2.
     *
     * @param selectorId
     * @param evt
     */
    module.synchronizeHiddenField = function (selectorId, evt) {
        var currentChoice = [];
        $('#' + evt.currentTarget.id + ' option:selected').each(function (idx, el) {
            currentChoice.push($(el).val());
        });
        $(selectorId).val(currentChoice.join(','));
    };

    /**
     * @param params
     * @param data
     * @returns {*}
     */
    module.select2Matcher = function (params, data) {
        var searchString = data.text;
        var modifiedData = $.extend({}, data, true);
        // if search string exists override
        if (data.element.dataset.searchString !== undefined) {
            searchString = data.element.dataset.searchString;
        }

        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
            return data;
        }

        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        if (searchString.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
            modifiedData.text += ' (matched)';

            // You can return modified objects from here
            // This includes matching the `children` how you want in nested data sets
            return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    };

    /**
     * Défini les options pour les select2Contact.
     *
     * @type {{templateResult: contactSelect, templateSelection: contactSelect, matcher: select2Matcher, dropdownParent: (any)}}
     */
    module.select2ContactOptions = {
        templateResult: module.contactSelect,
        templateSelection: module.contactSelect,
        matcher: module.select2Matcher,
        dropdownParent: $('#main-content')
    };

    /**
     * Affichage dans le 'placeholder' du résultat sélectionné.
     * Affichage des résultats.
     * Utiliser pour wantSelect2VilleOnFrance.
     *
     * @param repo
     * @returns {*}
     */
    module.formatRepoSelectionAdressePaysVilleOnFrance = function (repo) {
        if (!repo.name) {
            return repo.text;
        }

        return "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository'>"
            + repo.name +
            " (" + repo.cp + ") </div>" +
            "</div>";
    };

    /**
     * @param state
     * @returns {*}
     */
    module.formatState = function (state) {
        var $state = null;
        var value, text;

        if (!state.id) {
            return state.text;
        }

        value = state.element.dataset.value;
        text = state.text;
        $state = $('<span style="width:20px;height: 20px; background-color: ' + value + '; display: inline-block; vertical-align: middle;">&nbsp;</span> <span style="vertical-align: middle;">' + text + '</span>');

        return $state;
    };

    /**
     * @param state
     * @returns {*}
     */
    module.utilisateurAttributionState = function (state) {
        var $state = null;
        var pictureStr = null;

        if (!state.id) {
            return module.htmlTemplateUser(pictureStr, 'Sélectionnez un utilisateur', null);
        }

        if (state.element.dataset.userPicture.length > 0) {
            pictureStr = '<img src="' + state.element.dataset.userPicture + '" alt="" width="36" height="36">';
        } else {
            pictureStr = '<i class="glyphicon glyphicon-user" aria-hidden="true"></i>';
        }

        $state = $(module.htmlTemplateUser(pictureStr, state.element.dataset.userName, state.element.dataset.agenceName));

        return $state;
    };

    /**
     * @param state
     * @returns {*}
     */
    module.contactSelect = function (state) {
        var $state = null;
        var pictureStr = null;
        var userName, extraInfos;

        if (!state.id) {
            return module.htmlTemplateUser(pictureStr, 'Sélectionnez un contact', null);
            // return state.text;
        }

        userName = state.element.dataset.userName;
        extraInfos = state.element.dataset.extraInfos;
        pictureStr = '<i class="glyphicon glyphicon-user" aria-hidden="true"></i>';
        $state = $(module.htmlTemplateUser(pictureStr, userName, extraInfos));

        return $state;
    };

    /**
     * @param state
     * @returns {*}
     */
    module.dossierSelect = function (state) {
        var $state = null;
        var pictureStr = null;
        var userName, extraInfos;

        if (!state.id) {
            return module.htmlTemplateDossier(pictureStr, 'Sélectionnez un dossier', null);
            // return state.text;
        }

        userName = state.element.dataset.userName;
        extraInfos = state.element.dataset.extraInfos;
        pictureStr = '<i class="glyphicon glyphicon-folder-open" aria-hidden="true"></i>';
        $state = $(module.htmlTemplateDossier(pictureStr, userName, extraInfos));

        return $state;
    };

    /**
     * @param state
     * @returns {*}
     */
    module.terrainSelect = function (state) {
        var $state = null;
        var pictureStr = null;

        if (!state.id) {
            return module.htmlTemplateUser(pictureStr, 'Sélectionnez un terrain', null);
            // return state.text;
        }

        if (state.element.dataset.itemPicture.length > 0) {
            pictureStr = '<img src="' + state.element.dataset.itemPicture + '" alt="" width="36" height="36">';
        } else {
            pictureStr = '<i class="glyphicon glyphicon-flag" aria-hidden="true"></i>';
        }

        $state = $(module.htmlTemplateUser(pictureStr, state.element.dataset.itemName, state.element.dataset.extraInfos));

        return $state;
    };

    /**
     * Affichage dans le 'placeholder' du résultat sélectionné.
     * Affichage des résultats.
     * Utiliser pour wantSelect2Contact.
     *
     * @param repo
     * @returns {*}
     */
    module.formatRepoSelectionContact = function (repo) {
        var pictureStr = '<i class="glyphicon glyphicon-user" aria-hidden="true"></i>';
        var userName = null;
        var extraInfos = null;

        if (!repo.id) {
            return module.htmlTemplateUser(pictureStr, 'Sélectionnez un contact', null);
            // return;
        }

        if (repo.dataUserName !== undefined) {
            // on bosse avec la réponse xhr.
            userName = repo.dataUserName;
            extraInfos = repo.dataExtraInfos;
        } else {
            //On bosse avec l'objet dans le dom car option fraichement créé.
            userName = repo.element.dataset.userName;
            extraInfos = repo.element.dataset.extraInfos;
        }

        return module.htmlTemplateUser(pictureStr, userName, extraInfos);
    };

    /**
     * Affichage dans le 'placeholder' du résultat sélectionné.
     * Affichage des résultats.
     * Utiliser pour wantSelect2Dossier.
     *
     * @param repo
     * @returns {*}
     */
    module.formatRepoSelectionDossier = function (repo) {
        var pictureStr = '<i class="glyphicon glyphicon-folder-open" aria-hidden="true"></i>';
        var userName = null;
        var extraInfos = null;

        if (!repo.id) {
            return module.htmlTemplateDossier(pictureStr, 'Sélectionnez un dossier', null);
            // return;
        }

        if (repo.dataUserName !== undefined) {
            // on bosse avec la réponse xhr.
            userName = repo.dataUserName;
            extraInfos = repo.dataExtraInfos;
        } else {
            //On bosse avec l'objet dans le dom car option fraichement créé.
            userName = repo.element.dataset.userName;
            extraInfos = repo.element.dataset.extraInfos;
        }

        return module.htmlTemplateDossier(pictureStr, userName, extraInfos);
    };

    /**
     * Le template est toujours le même, donc on extrait la structure.
     *
     * @param pictureStr
     * @param userName
     * @param extraInfos
     * @returns {string}
     */
    module.htmlTemplateUser = function (pictureStr, userName, extraInfos) {
        return '<span class="user-info"><span class="user-img">'
            + pictureStr
            + '</span><span class="user-name">'
            + (userName !== null && userName !== undefined ? userName : 'Sélectionnez un contact')
            + '</span><span class="user-agency">'
            + (extraInfos !== null && extraInfos !== undefined ? extraInfos : '')
            + '</span></span>';
    };

    /**
     * Le template est toujours le même, donc on extrait la structure.
     *
     * @param pictureStr
     * @param userName
     * @param extraInfos
     * @returns {string}
     */
    module.htmlTemplateDossier = function (pictureStr, userName, extraInfos) {
        return '<span class="user-info"><span class="user-img">'
            + pictureStr
            + '</span><span class="user-name">'
            + (userName !== null && userName !== undefined ? userName : 'Sélectionnez un dossier')
            + '</span><span class="user-agency">'
            + (extraInfos !== null && extraInfos !== undefined ? extraInfos : '')
            + '</span></span>';
    };

    /**
     * Prépare les options pour la requete ajax.
     * Identique pour tous les select2.
     *
     * @param route
     * @param tplResult
     * @param tplSelection
     * @param options
     */
    module.select2AjaxOption = function (route, tplResult, tplSelection, options) {
        return {
            ajax: {
                url: Routing.generate(route, true),
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.length
                        }
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 2,
            templateResult: tplResult,
            templateSelection: tplSelection,
            dropdownParent: options.dropdownParent
        };
    };

    /**
     * Initialisation des select2
     */
    module.init = function () {
        $('.wantSelect2').select2({dropdownParent: $('#main-content')});

        $('.wantSelect2VilleOnFrance').select2(
            module.select2AjaxOption(
                'ville.api.search.whitelist',
                module.formatRepoSelectionAdressePaysVilleOnFrance,
                module.formatRepoSelectionAdressePaysVilleOnFrance,
                {dropdownParent: $('#main-content')}
            )
        );

        $('.wantSelect2Ville').select2(
            module.select2AjaxOption(
                'ville.api.search',
                module.formatRepoSelectionAdressePaysVilleOnFrance,
                module.formatRepoSelectionAdressePaysVilleOnFrance,
                {dropdownParent: $('#main-content')}
            )
        );

        $('.wantSelect2Contact').select2(
            module.select2AjaxOption(
                'contact.api.search',
                module.formatRepoSelectionContact,
                module.formatRepoSelectionContact,
                {dropdownParent: $('#main-content')}
            )
        );

        $('.wantSelect2Dossier').select2(
            module.select2AjaxOption(
                'dossier.api.search',
                module.formatRepoSelectionDossier,
                module.formatRepoSelectionDossier,
                {dropdownParent: $('#main-content')}
            )
        );

        $('.prestataireSelect2').select2(module.select2ContactOptions);
        $('.partenaireSelect2').select2(module.select2ContactOptions);
        $('.contactSelect2').select2(module.select2ContactOptions);
        $('.gerantSelect2').select2(module.select2ContactOptions);

        $('.couleur-select2 > select').select2({
            templateResult: module.formatState,
            templateSelection: module.formatState,
            dropdownParent: $('#main-content')
        });

        $('.wantSelect2.utilisateurAgence').select2({
            templateResult: module.utilisateurAttributionState,
            templateSelection: module.utilisateurAttributionState,
            matcher: module.select2Matcher,
            dropdownParent: $('#main-content')
        });

        $('.terrainSelect2').select2({
            templateResult: module.terrainSelect,
            templateSelection: module.terrainSelect,
            matcher: module.select2Matcher,
            dropdownParent: $('#main-content')
        });

        $('.modal').each(function () {
            $(this).find('.wantSelect2').select2({dropdownParent: $(this)});
        });

        $('.modal').each(function () {
            $(this).find('.wantSelect2VilleOnFrance').select2(
                module.select2AjaxOption(
                    'ville.api.search.whitelist',
                    module.formatRepoSelectionAdressePaysVilleOnFrance,
                    module.formatRepoSelectionAdressePaysVilleOnFrance,
                    {dropdownParent: $(this)}
                )
            );
        });

        $('.modal').each(function () {
            $(this).find('.wantSelect2Ville').select2(
                module.select2AjaxOption(
                    'ville.api.search',
                    module.formatRepoSelectionAdressePaysVilleOnFrance,
                    module.formatRepoSelectionAdressePaysVilleOnFrance,
                    {dropdownParent: $(this)}
                )
            );
        });

        /**
         * EVENTS SELECT2
         */
        $('.wantSelect2').on('select2:select', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });

        $('.wantSelect2').on('select2:unselect', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });

        $('.wantSelect2Contact').on('select2:select', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });

        $('.wantSelect2Contact').on('select2:unselect', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });

        $('.wantSelect2Dossier').on('select2:select', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });

        $('.wantSelect2Dossier').on('select2:unselect', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });

        $('.wantSelect2VilleOnFrance').on('select2:select', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });

        $('.wantSelect2VilleOnFrance').on('select2:unselect', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });

        $('.wantSelect2Ville').on('select2:select', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });

        $('.wantSelect2Ville').on('select2:unselect', function (evt) {
            var id = $(this).data('associated-field-id');
            module.synchronizeHiddenField('#' + id, evt);
        });
    };

    return module;
})();
