$(document).ready(function () {
    /**
     * Variables en dur
     */
    var idPaysFrance = 75;

    /**
     * Pour tous les titres de formulaires.
     */
    $('[data-toggle="tooltip"]').tooltip();

    /**
     * Boutons submit
     */
    $(document).on('submit', 'form', function() {
        var $form = $(this), $button;
        $form.find('[type=submit]').each(function() {
            $button = $(this);
            $('<button type="button" class="'+$button.attr('class')+'" disabled="disabled"><i class="fa fa-spinner fa-spin fa-fw"></i> '+$button.text()+'</button>').insertAfter($button);
            $button.addClass('hidden');
        });
    });

    /**
     * Indique si un form-group contient un help-block
     */
    $('.help-block').closest('.form-group').addClass('has-help');

    /**
     * Module de vérification des champs adresses.
     */
    ArobanAdresseVille.setIdPaysFrance(idPaysFrance);
    ArobanAdresseVille.initialisationAPV();
    ArobanAdresseVille.changement();

    /**
     * Potentiel
     */
    ArobanPotentiel.ratingPotentielOnChange();

    /**
     * Select2
     */
    ArobanSelect2.init();

    /**
     * TableCollapsible
     */
    ArobanTableCollapsible.init();
});
