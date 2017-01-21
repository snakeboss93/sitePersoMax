var menu = (function () {
    'use strict';
    var module = {};

    /**
     * Initialisation de redimensionement du menu (si mobile / desktop)
     */
    module.init = function () {
        window.addEventListener('resize', redimensionnement, false);
        searchUser();
    };

    /**
     * Fonction qui verifie si on redimmensionne l'ecran pour mobile
     */
    function redimensionnement() {
        if (window.matchMedia('(max-width: 768px)').matches) {
            $("#nav-total").addClass('hidden');
        } else {
            $("#nav-total").removeClass('hidden');
        }
    }

    /**
     * Initialisation du menu mobile
     */
    module.initMobile = function () {
        $("#menu-mobile-toggle").click(function () {
            $("#nav-total").toggleClass('hidden');
        });
    };

    /**
     * Requete ajax pour chercher les user.
     */
    function searchUser() {
        $("#search-user").select2({
            ajax: {
                url: "?action=searchUser",
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
            minimumInputLength: 3,
            templateResult: formatSelect2,
            templateSelection: redirectSelect
        });
    }

    /**
     * Affichage dans le 'placeholder' du résultat sélectionné.
     * Affichage des résultats.
     *
     * @param repo
     * @returns {*}
     */
    function formatSelect2(repo) {
        if (!repo.fullname) {
            return repo.text;
        }
        return '<span class="user-info">'
            + '<span class="user-img">'
            + '<img src=' + repo.img + ' alt="avatar" width="25" height="25"/>'
            + '</span><span class="user-name">'
            + repo.fullname
            + '</span></span>';
    }

    /**
     * Redirige si resultat selectionner.
     *
     * @param repo
     * @returns {*}
     */
    function redirectSelect(repo) {
        if (!repo.fullname) {
            return repo.text;
        }
        return window.location.replace('?action=profil&id=' + repo.id);
    }

    return module;
})();
