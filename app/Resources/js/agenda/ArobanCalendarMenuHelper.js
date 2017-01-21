/**
 * Module permettant de gérer les different menu et leur action d'un calendrier avec du personnel
 */
/* exported ArobanCalendarMenuHelper */
/* global ArobanCalendarHelper */
/* global moment */
/* jshint -W071 */
/* eslint vars-on-top: 0 */
var ArobanCalendarMenuHelper = (function () {
  'use strict';
  var module = {};

  /**
   * Listener pour le menu des evenemten.
   *
   * @param monEvent
   * @param IDCalendar
   * @param numberId
   */
  module.doActionMenu = function (monEvent, IDCalendar, numberId) {
    $('#button-go-stock').on('click', function () {
      makeEventInStock(monEvent, IDCalendar);
    });
    $('#button-change-duree').on('click', function () {
      changeDureeOnEvent(monEvent, IDCalendar, numberId);
    });
    $('#button-editable').on('click', function () {
      makeEventLock(monEvent, IDCalendar);
    });
  };

  /**
   * Permet de faire le menu d'action si on clique sur un event.
   *
   * @param event
   */
  module.makeMenu = function (event) {
    var infoWrapper = document.createElement('div');
    var btnWrapper = document.createElement('div');
    var goStockWrapper = document.createElement('div');
    var changeDureeWrapper = document.createElement('div');
    var editableWrapper = document.createElement('div');
    var suivreLienWrapper = document.createElement('div');
    var goStock = document.createElement('button');
    var changeDuree = document.createElement('button');
    var editable = document.createElement('button');
    var suivreLien = document.createElement('a');
    var buttonClose = document.createElement('button');

    buttonClose.type = 'button';
    buttonClose.className = 'close';
    buttonClose.id = 'close-menu-event';
    buttonClose.innerHTML = '<i class="glyphicon glyphicon-remove"></i>';
    infoWrapper.className = 'info-wrapper';
    btnWrapper.className = 'btn-wrapper';
    goStock.innerHTML = 'Retirer de l\'agenda';
    goStock.className = 'btn btn-info';
    goStock.id = 'button-go-stock';
    goStockWrapper.className = 'menu-event-button-wrapper';
    goStockWrapper.appendChild(goStock);
    changeDuree.innerHTML = 'Modifier la durée';
    changeDuree.className = 'button-changeDuree btn btn-info';
    changeDuree.id = 'button-change-duree';
    changeDureeWrapper.className = 'menu-event-button-wrapper';
    changeDureeWrapper.appendChild(changeDuree);
    editable.innerHTML = '(Dé)verrouiller';
    editable.className = 'button-editable btn btn-info';
    editable.id = 'button-editable';
    editableWrapper.className = 'menu-event-button-wrapper';
    editableWrapper.appendChild(editable);
    suivreLien.innerHTML = 'Suivre le lien';
    suivreLien.className = 'button-suivreLien btn btn-default';
    suivreLien.role = 'button';
    suivreLien.href = '#';
    suivreLien.id = 'button-suivreLien';
    suivreLienWrapper.className = 'menu-event-button-wrapper';
    suivreLienWrapper.appendChild(suivreLien);

    infoWrapper.appendChild(makeEventHtmlToMenu(event, false, true));
    btnWrapper.appendChild(goStockWrapper);
    btnWrapper.appendChild(changeDureeWrapper);
    btnWrapper.appendChild(editableWrapper);
    btnWrapper.appendChild(suivreLienWrapper);
    $('#menu-event').html(buttonClose).append(infoWrapper, btnWrapper);

    $('#close-menu-event').click(function () {
      $('#menu-event').html('');
    });
  };

  /**
   * Fabrique un event afin de l'afficher dans un des menu (externe ou d'action).
   *
   * @param event
   * @param draggable bool
   * @param menu
   */
  function makeEventHtmlToMenu(event, draggable, menu) {
    var eventA = document.createElement('a');
    var fcContent = document.createElement('div');
    var fcTitle = document.createElement('div');
    var fcClient = document.createElement('span');
    var fcTrigrammeAuteur = document.createElement('span');
    var fcCouleurAgence = document.createElement('span');
    var fcDateretour = document.createElement('span');
    var fcStatus = document.createElement('span');
    var fcEditable = document.createElement('span');
    var fcNbQuartJournee = document.createElement('span');
    var fcId = document.createElement('span');
    var start = document.createElement('span');
    var resource = document.createElement('span');
    var fcbg = document.createElement('div');

    eventA.appendChild(fcContent);
    eventA.appendChild(fcbg);
    fcContent.appendChild(fcTitle);
    fcContent.appendChild(fcClient);
    fcContent.appendChild(fcTrigrammeAuteur);
    fcContent.appendChild(fcCouleurAgence);
    fcContent.appendChild(fcDateretour);
    fcContent.appendChild(fcStatus);
    fcContent.appendChild(fcEditable);
    fcContent.appendChild(fcNbQuartJournee);
    fcContent.appendChild(fcId);

    if (true !== menu) {
      start.className = 'fc-start hidden';
      start.id = 'fc-start-event-id-' + event.id;
      start.innerHTML = event.start.format();
      fcContent.appendChild(start);
      resource.className = 'fc-resource-id hidden';
      resource.id = 'fc-resource-id-event-id-' + event.id;
      resource.innerHTML = event.resourceId;
      fcContent.appendChild(resource);
    }

    eventA.className = true === draggable ?
      'fc-time-grid-event fc-v-event fc-event fc-start fc-end fc-draggable ui-draggable ui-draggable-handle drop-remove'
      : 'fc-time-grid-event fc-v-event fc-event fc-start fc-end';
    eventA.id = 'event-draggable-' + event.id;
    fcbg.className = 'fc-bg';
    fcContent.className = 'fc-content';
    fcTitle.className = 'fc-title';
    fcTitle.id = 'fc-title-event-id-' + event.id;
    fcClient.className = 'fc-client';
    fcClient.id = 'fc-client-event-id-' + event.id;
    fcTrigrammeAuteur.className = 'fc-trigrammeAuteur';
    fcTrigrammeAuteur.id = 'fc-trigrammeAuteur-event-id-' + event.id;
    fcCouleurAgence.className = 'fc-couleurAgence hidden';
    fcCouleurAgence.id = 'fc-couleurAgence-event-id-' + event.id;
    fcDateretour.className = 'fc-dateRetour';
    fcDateretour.id = 'fc-dateRetour-event-id-' + event.id;
    fcStatus.className = 'fc-status';
    fcStatus.id = 'fc-status-event-id-' + event.id;
    fcEditable.className = 'fc-editable hidden';
    fcEditable.id = 'fc-editable-event-id-' + event.id;
    fcNbQuartJournee.className = 'fc-nbQuartJournee hidden';
    fcNbQuartJournee.id = 'fc-nbQuartJournee-event-id-' + event.id;
    fcId.className = 'fc-id hidden';
    fcId.id = 'fc-id-event-id-' + event.id;
    fcTitle.innerHTML = event.title;
    fcClient.innerHTML = event.client;
    fcTrigrammeAuteur.innerHTML = event.trigrammeAuteur;
    fcCouleurAgence.innerHTML = event.couleurAgence;
    fcDateretour.innerHTML = event.dateRetour;
    fcStatus.innerHTML = event.status;
    fcEditable.innerHTML = event.editable;
    fcNbQuartJournee.innerHTML = event.nbQuartJournee;
    fcId.innerHTML = event.id;

    eventA.className += ' status-' + event.status + ' event-' + event.couleurAgence;

    return eventA;
  }

  /**
   * Fabrique un event dans le menu stock (externe).
   *
   * @param event
   * @param IDCalendar
   */
  function makeEventInStock(event, IDCalendar) {
    if ('Congé' !== event.title && 'Réunion' !== event.title) {
      $('#external-events .fc-event-container').append(makeEventHtmlToMenu(event, true, true));
    }
    $('#menu-event').html('');
    $(IDCalendar).fullCalendar('removeEvents', event.id);
    ArobanCalendarHelper.makeEventsDraggable();
    ArobanCalendarHelper.sendEvent($(IDCalendar).fullCalendar('clientEvents'));
  }

  /**
   * Permet d'ouvrir une pop up pour changer la valeur de la durée d'un event.
   *
   * @param monEvent
   * @param IDCalendar
   */
  function changeDureeOnEvent(monEvent, IDCalendar) {
    var newNbQuartJournee = 0;
    do {
      newNbQuartJournee = parseInt(window.prompt('Entrez une nouvelle durée', monEvent.nbQuartJournee), ArobanCalendarHelper.base);
    } while (0 >= newNbQuartJournee || 20 < newNbQuartJournee || isNaN(newNbQuartJournee));
    monEvent.nbQuartJournee = newNbQuartJournee;
    monEvent.duration = ArobanCalendarHelper.calculQuartJournee(newNbQuartJournee);
    monEvent.end = moment(monEvent.start).add(moment.duration(monEvent.duration));
    $('#fc-nbQuartJournee-event-id-' + monEvent.id).html(newNbQuartJournee);
    ArobanCalendarHelper.eventManyDaysProcess(IDCalendar, monEvent);
    ArobanCalendarHelper.makeEventsDraggable();
    ArobanCalendarHelper.rerenderAllCalendar();
    ArobanCalendarHelper.sendEvent($(IDCalendar).fullCalendar('clientEvents'));
  }

  /**
   * Verrouille l'event.
   *
   * @param monEvent
   * @param IDCalendar
   */
  function makeEventLock(monEvent, IDCalendar) {
    var idEditable = '#fc-editable-event-id-' + monEvent.id;
    if ('true' === $(idEditable).html()) {
      $(idEditable).html('false');
      monEvent.editable = false;
      $(idEditable).parent().parent().draggable({disabled: true});
    } else {
      $(idEditable).html('true');
      monEvent.editable = true;
      $(idEditable).parent().parent().draggable({disabled: false});
    }

    $(idEditable).parent().parent().toggleClass('fc-draggable');
    $(idEditable).parent().parent().toggleClass('ui-draggable');
    $(idEditable).parent().parent().toggleClass('ui-draggable-handle');
    $(IDCalendar).fullCalendar('rerenderEvents');
    ArobanCalendarHelper.sendEvent($(IDCalendar).fullCalendar('clientEvents'));
  }

  return module;
})();
