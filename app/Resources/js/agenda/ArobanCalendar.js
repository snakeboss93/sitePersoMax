/**
 * Module permettant de gérer un calendrier avec du personnel.
 */
/* exported ArobanCalendar */
/* global ArobanCalendarHelper */
/* global ArobanCalendarMenuHelper */
/* global moment */
/* global JSON */
/* eslint vars-on-top: 0 */
/* jshint -W071 */
/* jshint -W098 */
var ArobanCalendar = (function () {
  'use strict';
  var module = {};

  /**
   * Permet de stocker les resources (concepteur BEA / Agence) pour les initialiser et les distribuer.
   *
   * Need porté global
   *
   * @type Array
   */
  var concepteurJson = [];

  /**
   * Permet de stocker les events pour les initialiser et les distribuer.
   *
   * Need porté global
   *
   * @type Array
   */
  var eventsJson = [];

  /**
   * Variable qui permet de porter les évènements d'une méthode à une autre.
   *
   * Need porté global
   *
   * @type {{}}
   */
  var calEventStatus = {
    calendar: null,
    event_id: null,
    event: null
  };

  /**
   * Méthode public pour démarer l'initialisation et l'éxécution des calendriers.
   *
   * @param data
   * @param concepteur
   */
  module.start = function (data, concepteur) {
    ArobanCalendarHelper.start('#calendar');
    concepteurJson = JSON.parse(concepteur);
    eventsJson = JSON.parse(data);
    initEvents();
    ArobanCalendarHelper.makeEntete(concepteurJson);
    initCalendars(ArobanCalendarHelper.baseIdDivCalendar);
    $('#main-content').addClass('col-count-' + concepteurJson.length);
    ArobanCalendarHelper.suppressManyDaysOnLoad();
    ArobanCalendarHelper.makeEventsDraggable();
    ArobanCalendarHelper.makeFilsManyDaysOnLoad();
    ArobanCalendarHelper.rerenderAllCalendar();
  };

  /**
   * Ici on initialise les évènements reçus en JSON interne au calendar.
   */
  function initEvents() {
    for (var i = 0; i < eventsJson.length; i++) {
      eventsJson[i].id = eventsJson[i].id.toString();
      eventsJson[i].duration = ArobanCalendarHelper.calculQuartJournee(eventsJson[i].nbQuartJournee);
      eventsJson[i].end = moment(eventsJson[i].start).add(moment.duration(eventsJson[i].duration));
    }
  }

  /**
   * Initialise plusieurs calendriers sur une même page dans une boucle pour modifier les dates.
   * Exclut les week-ends.
   *
   * @param idDivCalendar
   */
  function initCalendars(idDivCalendar) {
    var laDate;
    var limit = 11;
    var idDiv = 0;
    var spanWeekNumber = document.createElement('span');
    spanWeekNumber.className = 'fc-week-number';
    for (var j = 0; j < limit; j++) {
      laDate = moment().add(j, 'days');
      if (6 !== laDate.isoWeekday() && 7 !== laDate.isoWeekday()) {
        initCalendar(laDate.toISOString(), idDivCalendar + idDiv);
        $(idDivCalendar + idDiv + ' .fc-toolbar').append(spanWeekNumber.innerHTML = laDate.isoWeek());
        idDiv++;
      } else {
        limit++;
      }
    }
  }

  /**
   * Initialise les calendriers et indique comment les events doivent être gérés.
   *
   * @param date
   * @param ID
   */
  function initCalendar(date, ID) {
    var numberId = ArobanCalendarHelper.getNumberIdDiv(ID);
    $(ID).fullCalendar({
      schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
      defaultView: 'agendaDay',
      weekNumbers: false,
      defaultDate: date,
      locale: 'fr',
      editable: true,
      minTime: '00:00:00',
      maxTime: '24:00:00',
      slotDuration: '6:00:00',
      slotEventOverlap: false,
      eventOverlap: true,
      selectOverlap: false,
      eventDurationEditable: false,
      eventLimit: true,
      titleFormat: 'ddd D MMM',
      header: {
        left: 'title',
        center: '',
        right: ''
      },
      allDaySlot: false,
      resources: concepteurJson,
      events: eventsJson,
      droppable: true,
      displayEventTime: false,
      dragRevertDuration: 0,
      eventRender: function (event, element) {
        ArobanCalendarHelper.doEventRender(event, element);
      },
      eventDragStart: function (event) {
        calEventStatus.calendar = ID;
        calEventStatus.event_id = event._id;
        calEventStatus.event = event;
      },
      eventDrop: function () {
        $(calEventStatus.calendar).fullCalendar('removeEvents', calEventStatus.event_id);
      },
      drop: function (date, jsEvent, ui, resourceId) {
        // console.log('drop');

        if (null !== calEventStatus.event && 'undefined' !== typeof calEventStatus.event) {
          calEventStatus.event.start = date;
          calEventStatus.event.resourceId = resourceId;
          ArobanCalendarHelper.eventManyDaysProcess(ID, calEventStatus.event, false);
        }
        if ('undefined' !== typeof calEventStatus.calendar) {
          if (ID !== calEventStatus.calendar) {
            $(calEventStatus.calendar).fullCalendar('removeEvents', calEventStatus.event_id);
          }
        }

        if ($(this).hasClass('drop-remove')) {
          $(this).remove();
        }
        calEventStatus = {};

        ArobanCalendarHelper.sendEvent($(ID).fullCalendar('clientEvents'));
      },
      eventClick: function (event) {
        // console.log('eventClick');

        if (-1 === event.id.toString().indexOf('fils')) {
          ArobanCalendarMenuHelper.makeMenu(event);
          ArobanCalendarMenuHelper.doActionMenu(event, ID, numberId);
        }
      },
      eventAfterRender: function (event, element) {
        ArobanCalendarHelper.makeOneEventDraggable(element[0]);
      }
    });
  }

  return module;
})();
