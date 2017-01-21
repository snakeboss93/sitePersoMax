/* exported ArobanPotentiel */
var ArobanPotentiel = (function () {
    'use strict';
    var module = {};

    /**
     *
     */
    module.potentielFaible = function () {
        $("label[for$='potentiel_faible']").addClass('rating-checked');
        $("label[for$='potentiel_moyen']").removeClass('rating-checked');
        $("label[for$='potentiel_fort']").removeClass('rating-checked');
        $("label[for$='potentiel_zero']").removeClass('no-rating-checked');
    };

    /**
     *
     */
    module.potentielMoyen = function () {
        $("label[for$='potentiel_faible']").addClass('rating-checked');
        $("label[for$='potentiel_moyen']").addClass('rating-checked');
        $("label[for$='potentiel_fort']").removeClass('rating-checked');
        $("label[for$='potentiel_zero']").removeClass('no-rating-checked');
    };

    /**
     *
     */
    module.potentielFort = function () {
        $("label[for$='potentiel_faible']").addClass('rating-checked');
        $("label[for$='potentiel_moyen']").addClass('rating-checked');
        $("label[for$='potentiel_fort']").addClass('rating-checked');
        $("label[for$='potentiel_zero']").removeClass('no-rating-checked');
    };

    /**
     * Permet de modifier l'affichage de la note du potentiel en fonction de sa note.
     */
    module.ratingPotentielOnChange = function () {
        $('.rating-potentiel').change(function () {


            if ($("[id$='potentiel_faible']").is(':checked')) {
                module.potentielFaible();
            }
            if ($("[id$='potentiel_moyen']").is(':checked')) {
                module.potentielMoyen();
            }
            if ($("[id$='potentiel_fort']").is(':checked')) {
                module.potentielFort();
            }
        });

        $('.rating-potentiel-none').change(function () {
            if ($("[id$='potentiel_zero']").is(':checked')) {
                $("label[for$='potentiel_zero']").addClass('no-rating-checked');
                $("label[for$='potentiel_faible']").removeClass('rating-checked');
                $("label[for$='potentiel_moyen']").removeClass('rating-checked');
                $("label[for$='potentiel_fort']").removeClass('rating-checked');
            }
        });
    };

    return module;
})();