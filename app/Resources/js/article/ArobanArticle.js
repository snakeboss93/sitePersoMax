/* exported ArobanArticle */
var ArobanArticle = (function () {
    'use strict';
    var module = {};

    module.onPageLoadEdit = function () {
        $('input:checkbox').each(function () {
            var associatedFields = $(this).data('associated-fields');
            if (this.checked === false) {
                $('input[data-associated-checkbox='+associatedFields+']').attr('disabled', 'disabled');
            }
        });

        $('input:checkbox').on('click', function () {
            var associatedFields = $(this).data('associated-fields');
            var associatedSociete = $(this).data('associated-societe');
            if (this.checked) {
                $('input[data-associated-checkbox='+associatedFields+']').removeAttr('disabled');
                if ($('#'+associatedSociete+'-checkbox').is(':checked') === false) {
                    $('#'+associatedSociete+'-checkbox').click();
                }
            } else {
                $('input[data-associated-checkbox='+associatedFields+']').attr('disabled', 'disabled');
            }
        });
    };

    return module;
})();

