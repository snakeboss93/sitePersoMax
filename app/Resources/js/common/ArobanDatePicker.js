/**
 * DatePicker pour les champs avec une class = [dateTimePicker, datePicker, datePickerDefaultVide, datePickerEdit]
 */

var ArobanDatePicker = (function () {
    'use strict';
    var module = {};

    /**
     * Icones.
     */
    module.optionsIcons = {
        time: 'glyphicon glyphicon-time',
        date: 'glyphicon glyphicon-calendar',
        up: 'glyphicon glyphicon-chevron-up',
        down: 'glyphicon glyphicon-chevron-down',
        previous: 'glyphicon glyphicon-chevron-left',
        next: 'glyphicon glyphicon-chevron-right',
        today: 'glyphicon glyphicon-screenshot',
        clear: 'glyphicon glyphicon-trash',
        close: 'glyphicon glyphicon-remove'
    };

    /**
     * tooltips.
     */
    module.optionsTooltips = {
        selectMonth: 'Sélectionner le mois',
        prevMonth: 'Mois précédent',
        nextMonth: 'Mois suivant',
        selectYear: 'Choisir une année',
        prevYear: 'Année précédente',
        nextYear: 'Année suivante'
    };

    /**
     * Options pour la création.
     */
    module.options = {
        format: 'DD/MM/YYYY',
        dayViewHeaderFormat: 'MMMM YYYY',
        locale: 'fr',
        sideBySide: true,
        daysOfWeekDisabled: [0, 6],
        disabledHours: [0, 1, 2, 3, 4, 5, 6, 21, 22, 23],
        defaultDate: new Date(),
        showTodayButton: true,
        tooltips: module.optionsTooltips,
        widgetPositioning: {
            vertical: 'bottom'
        },
        icons: module.optionsIcons,
        keepOpen: true
    };

    /**
     * Options pour l'édition.
     */
    module.optionsEdit = {
        format: 'DD/MM/YYYY',
        dayViewHeaderFormat: 'MMMM YYYY',
        locale: 'fr',
        sideBySide: true,
        daysOfWeekDisabled: [0, 6],
        disabledHours: [0, 1, 2, 3, 4, 5, 6, 21, 22, 23],
        useCurrent: false,
        showTodayButton: true,
        tooltips: module.optionsTooltips,
        widgetPositioning: {
            vertical: 'bottom'
        },
        icons: module.optionsIcons,
        keepOpen: true
    };

    return module;
})();

var ArobanDateTimePicker = (function () {
    'use strict';
    var module = {};

    /**
     * Options pour l'édition.
     */
    module.options = {
        dayViewHeaderFormat: 'MMMM YYYY',
        locale: 'fr',
        sideBySide: true,
        daysOfWeekDisabled: [0, 6],
        disabledHours: [0, 1, 2, 3, 4, 5, 6, 21, 22, 23],
        defaultDate: new Date(),
        useCurrent: false,
        showTodayButton: true,
        tooltips: ArobanDatePicker.optionsTooltips,
        widgetPositioning: {
            vertical: 'bottom'
        },
        icons: ArobanDatePicker.optionsIcons,
        keepOpen: true
    };

    return module;
})();


$(document).ready(function () {
    $('.dateTimePicker').each(function () {
        $(this).datetimepicker(ArobanDateTimePicker.options);
    });

    $('.datePicker').each(function () {
        $(this).datetimepicker(ArobanDatePicker.options);
    });

    $('.datePickerDefaultVide').each(function () {
        var currentDate = $(this).attr('value');

        if (currentDate === 'undefined') {
            $(this).datetimepicker(ArobanDatePicker.options).val('');
        } else {
            $(this).datetimepicker(ArobanDatePicker.options).val(currentDate);
        }
    });

    $('.datePickerEdit').each(function () {
        $(this).datetimepicker(ArobanDatePicker.optionsEdit);
    });
});
