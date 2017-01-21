/* exported ArobanCombinaison */
var ArobanCombinaison = (function () {
    'use strict';
    var module = {};
    var nbTrVisible;
    var selectorNoResults = $('#no-results');

    /**
     * Filtrer la liste des combinaisons.
     */
    module.filterList = function() {
        var selectedValues = [];
        $('input[name^="caracteristique"]:checked').each(function (key, ck) {
            selectedValues.push(ck.value);
        });

        $(".table-combinaisons .table-combinaisons-item").each(function(key, tr) {
            var categories = $(tr).data('category').split(',');
            var hide = false;
            $(tr).find('input[type=radio]:checked').prop('checked', false).trigger('change');
            selectorNoResults.hide();

            selectedValues.forEach(function (el) {
                if (!hide) {
                    hide = (categories.indexOf(el) === -1);
                }
            });

            if (hide) {
                $(tr).hide();
            } else {
                $(tr).show();
            }

            nbTrVisible = $(".table-combinaisons .table-combinaisons-item:visible").length;

            if (nbTrVisible === 0) {
                selectorNoResults.show();
            }
        });
    };

    /**
     * Initialisation.
     */
    module.init = function() {
        var selectorCheckboxCombinaisons = 'input[name^=combinaison]';

        $(selectorCheckboxCombinaisons).click(function() {
            var currentChoice = $(selectorCheckboxCombinaisons+':checked').map(function (key, el) {
                return el.value;
            }).get();

            $('#nomenclature_article_famille_ouvrage_combinaisons').val(currentChoice.join(','));
        });

        $('.panel-search input[type=radio]').change(function() {
            var filter = $(this).val();
            module.filterList(filter);
        });
    };

    return module;
})();
