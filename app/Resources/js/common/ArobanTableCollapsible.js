/* exported ArobanTableCollapsible */
var ArobanTableCollapsible = (function () {
    'use strict';
    var module = {};

    /**
     * Initialisation
     */
    module.init = function () {
        $('.collapse-all').on('click', function () {
            if ($(this).attr('aria-expanded') === 'false') {
                $(this).closest('table').find('.collapse').collapse('show');
                $(this).attr("aria-expanded", "true");
            } else {
                $(this).closest('table').find('.collapse').collapse('hide');
                $(this).attr("aria-expanded", "false");
            }
        });
    };

    return module;
})();
