/**
 * Module permettant de d'aider la gestion d'un calendrier avec du personnel.
 */
/* exported ArobanCalendarHelper */
/* global moment */
/* jshint -W071 */
/* eslint vars-on-top: 0 */
var ArobanCalendarHelper = (function () {
  'use strict';
  var module = {};

  /**
   * Permet de détecter les doublons dans le calendrier afin de leur attribuer un traitement particulier.
   *
   * Need porté global
   *
   * @type int
   */
  var doublonsId;

  /**
   * Permet d'avoir le dernier id d'agendaItem.
   *
   * Need porté global
   *
   * @type int
   */
  var lastAgendaItemId;

  /**
   * Constante (déclarer var pour compatiblité)
   * Connaître la base pour tous les parseInt.
   *
   * @type {number}
   */
  module.base = 10;

  /**
   * Constante (déclarer var pour compatiblité)
   * Connaître la base de l'id du calendar.
   * Valeur par défaut, sinon set à l'appel.
   *
   * @type {string}
   */
  module.baseIdDivCalendar = '#calendar';

  /**
   * Méthode public pour démarer l'initialisation des calendriers avec le nom des calendar passer en parametre.
   *
   * @param baseIdDivCalendar
   */
  module.start = function (baseIdDivCalendar) {
    module.baseIdDivCalendar = baseIdDivCalendar;
    lastAgendaItemId = getLastAgendaItem();
  };

  /**
   * Méthode qui creer les fils de l'event passer en parametre d'un certains calendar passer aussi en parametre.
   *
   * @param calendarId
   * @param event
   * @param onLoad
   */
  module.eventManyDaysProcess = function (calendarId, event, onLoad) {
    var startString = event.start.format();
    var startH = parseInt(moment(startString).format('H'), module.base);
    var nbQuartJournee = parseInt($('#fc-nbQuartJournee-event-id-' + event.id).html(), module.base);
    var nbQuartJourneeCalcul = nbQuartJournee - module.calculHeureDeDepart(startH);
    var numeroId = parseInt(module.getNumberIdDiv(calendarId), module.base) + 1;
    var vraiNbFils = module.calculNbFilsOfPere(nbQuartJournee, startString);
    var eventA = null;
    if (!onLoad) {
      module.suppressionFils(event.id, module.calculNbFilsOfPere(nbQuartJournee, startString));
    }

    for (var i = 0; i < vraiNbFils; i++) {
      eventA = module.makeFilsEvent(event, nbQuartJourneeCalcul, startString, i);
      $(module.baseIdDivCalendar + numeroId).fullCalendar('renderEvent', eventA, false);
      startString = eventA.start;
      startH = parseInt(moment(startString).format('H'), module.base);
      nbQuartJourneeCalcul = eventA.nbQuartJournee - module.calculHeureDeDepart(parseInt(moment(startString).format('H'), module.base));
      numeroId++;
    }
  };

  /**
   * Permet de retourner un event fictif a partir de son id.
   * Utiliser pour reconstruire un event, pas de manipuler le vrai (au sens fullcalendar).
   *
   * @param event
   * @param nbQuartJournee
   * @param startString
   * @param nbFils
   * @returns {{}}
   */
  module.makeFilsEvent = function (event, nbQuartJournee, startString, nbFils) {
    var eventFils = {};
    var start = moment(startString);
    if (5 !== start.isoWeekday()) {
      start.add(1, 'days').hours(0);
    } else {
      start.add(3, 'days').hours(0);
    }

    eventFils.id = event.id + '-fils-' + nbFils;
    eventFils.title = $('#fc-title-event-id-' + event.id).html();
    eventFils.client = $('#fc-client-event-id-' + event.id).html();
    eventFils.trigrammeAuteur = $('#fc-trigrammeAuteur-event-id-' + event.id).html();
    eventFils.couleurAgence = $('#fc-couleurAgence-event-id-' + event.id).html();
    eventFils.dateRetour = $('#fc-dateRetour-event-id-' + event.id).html();
    eventFils.status = $('#fc-status-event-id-' + event.id).html();
    eventFils.resourceId = event.resourceId;
    eventFils.editable = false;
    eventFils.nbQuartJournee = nbQuartJournee;
    eventFils.duration = module.calculQuartJournee(eventFils.nbQuartJournee);
    eventFils.start = start.format();
    eventFils.end = moment(eventFils.start).add(moment.duration(eventFils.duration));

    return eventFils;
  };

  /**
   * Supprime les evenement fils au chargement des calendars.
   * (suppression comportement par défaut de fullcalendar qui n'est pas adapté a nos besoins.)
   */
  module.suppressManyDaysOnLoad = function () {
    var IDCalendar = '';
    var sousElt = $('.fc-content');
    var elts = null;
    var elts2 = null;
    var calendar = $("[id^='calendar']");
    for (var k = 0; k < calendar.length; k++) {
      elts = $(calendar[k]).find(sousElt);
      for (var l = k + 1; l < calendar.length; l++) {
        elts2 = $(calendar[l]).find(sousElt);
        for (var i = 0; i < elts.length; i++) {
          for (var j = 0; j < elts2.length; j++) {
            if (elts[i].firstChild.id === elts2[j].firstChild.id) {
              IDCalendar = '#' + $(calendar[l])[0].id;
              $(IDCalendar).fullCalendar('removeEvents', elts2[j].children[8].innerHTML);
            }
          }
        }
      }
    }
  };

  /**
   * Méthode qui au chargement de la page installe les fils sur les calendrier.
   */
  module.makeFilsManyDaysOnLoad = function () {
    var IDCalendar = null;
    var events = null;
    var calendar = $("[id^='calendar']");
    for (var k = 0; k < calendar.length; k++) {
      IDCalendar = '#' + $(calendar[k])[0].id;
      events = $(IDCalendar).fullCalendar('getEventSources');
      for (var i = 0; i < events[0].events.length; i++) {
        module.eventManyDaysProcess(IDCalendar, events[0].events[i], true);
      }
    }
  };

  /**
   * Permet de recalculer l'affichage de tous les events sur tous les calendar et supprime les doublons.
   */
  module.rerenderAllCalendar = function () {
    var IDCalendar = '';
    $("[id^='calendar']").each(function () {
      IDCalendar = '#' + $(this)[0].id;
      $(IDCalendar).fullCalendar('rerenderEvents');
    });
  };

  /**
   * indique le nombre de fils qu'un event doit avoir via la nbQuartJournee.
   *
   * @param nbQuartJournee
   * @param startString
   * @returns int
   */
  module.calculNbFilsOfPere = function (nbQuartJournee, startString) {
    var fils = 0;
    var startH = parseInt(moment(startString).format('H'), module.base);
    nbQuartJournee = nbQuartJournee - module.calculHeureDeDepart(startH);
    while (0 < nbQuartJournee) {
      fils++;
      nbQuartJournee -= 4;
    }

    return fils;
  };

  /**
   * Supprime tous les fils d'un eventement pere.
   *
   * @param eventId
   * @param nbFils
   */
  module.suppressionFils = function (eventId, nbFils) {
    $("[id^='calendar']").each(function () {
      for (var i = 0; i < nbFils + 1; i++) {
        $('#' + $(this)[0].id).fullCalendar('removeEvents', eventId + '-fils-' + i);
      }
    });
  };

  /**
   * Permet d'ajouter des span sur les events.
   *
   * @param event
   * @param element
   */
  module.doEventRender = function (event, element) {
    var client = document.createElement('span');
    var trigrammeAuteur = document.createElement('span');
    var couleurAgence = document.createElement('span');
    var dateRetour = document.createElement('span');
    var status = document.createElement('span');
    var editable = document.createElement('span');
    var nbQuartJournee = document.createElement('span');
    var id = document.createElement('span');
    var start = document.createElement('span');
    var resource = document.createElement('span');
    var nbFils = document.createElement('span');

    client.innerHTML = event.client;
    client.className = 'fc-client';
    client.id = 'fc-client-event-id-' + event.id;
    trigrammeAuteur.innerHTML = event.trigrammeAuteur;
    trigrammeAuteur.className = 'fc-trigrammeAuteur';
    trigrammeAuteur.id = 'fc-trigrammeAuteur-event-id-' + event.id;
    couleurAgence.innerHTML = event.couleurAgence;
    couleurAgence.className = 'fc-couleurAgence hidden';
    couleurAgence.id = 'fc-couleurAgence-event-id-' + event.id;
    dateRetour.innerHTML = event.dateRetour;
    dateRetour.className = 'fc-dateRetour';
    dateRetour.id = 'fc-dateRetour-event-id-' + event.id;
    status.innerHTML = event.status;
    status.className = 'fc-status';
    status.id = 'fc-status-event-id-' + event.id;
    editable.innerHTML = event.editable;
    editable.className = 'fc-editable hidden';
    editable.id = 'fc-editable-event-id-' + event.id;
    nbQuartJournee.innerHTML = event.nbQuartJournee;
    nbQuartJournee.className = 'fc-nbQuartJournee hidden';
    nbQuartJournee.id = 'fc-nbQuartJournee-event-id-' + event.id;
    id.innerHTML = event.id;
    id.className = 'fc-id hidden';
    id.id = 'fc-id-event-id-' + event.id;
    start.innerHTML = event.start.format();
    start.className = 'fc-start hidden';
    start.id = 'fc-start-event-id-' + event.id;
    resource.innerHTML = event.resourceId;
    resource.className = 'fc-resource-id hidden';
    resource.id = 'fc-resource-id-event-id-' + event.id;

    nbFils.innerHTML = module.calculNbFilsOfPere(event.nbQuartJournee, event.start.format()).toString();
    nbFils.className = 'fc-id-have-fils-id hidden';
    nbFils.id = 'fc-id-have-fils-event-id-' + event.id;

    if (false === event.editable) {
      element[0].className += ' fc-locked';
    }
    element[0].className += ' status-' + event.status + ' event-' + event.couleurAgence;
    element[0].id = 'event-draggable-' + event.id;
    element[0].firstChild.firstChild.id = 'fc-title-event-id-' + event.id;
    element[0].firstChild.append(client);
    element[0].firstChild.append(trigrammeAuteur);
    element[0].firstChild.append(couleurAgence);
    element[0].firstChild.append(dateRetour);
    element[0].firstChild.append(status);
    element[0].firstChild.append(editable);
    element[0].firstChild.append(nbQuartJournee);
    element[0].firstChild.append(id);
    element[0].firstChild.append(start);
    element[0].firstChild.append(resource);
    element[0].firstChild.append(nbFils);
  };

  /**
   * Retourne sous le bon format la durée d'un event en fonction du nombre de quarts de journée.
   *
   * @param nombre
   * @returns string
   */
  module.calculQuartJournee = function (nombre) {
    return (nombre * 6).toString() + ':00:00';
  };

  /**
   * Retourne un int qui donne l'heure de nbQuarJournee en moins en vu d'un evenement double (sur deux jours).
   *
   * @param nombre
   * @returns int
   */
  module.calculHeureDeDepart = function (nombre) {
    switch (nombre) {
      case 0:
        return 4;
      case 6:
        return 3;
      case 12:
        return 2;
      default:
        return 1;
    }
  };

  /**
   * Permet de traiter les doublons quand on rend un event draggable.
   *
   * @param element
   * @param id
   */
  module.traitementDoublons = function (element, id) {
    if (doublonsId !== id) {
      doublonsId = id;
    } else {
      //$(element).remove(); // todo a voir si on en as reellement besoins
      $('.fc-draggable').each(function () {
        var id2 = element.children[0].children[8] ? parseInt(element.children[0].children[8].innerHTML, module.base) : null;
        if (doublonsId === id2) {
          $(element).css('left', '0%');
          $(element).css('right', '0%');
        }
      });
      doublonsId = 0;
    }
  };

  /**
   * Rend l'event draggable s'il le faut.
   *
   * @param element
   * @param editable
   */
  module.draggableEvent = function (element, editable) {
    if ('false' === editable) {
      $(element).parent().parent().draggable({disabled: true});
      $(element).parent().parent().toggleClass('fc-draggable');
      $(element).parent().parent().toggleClass('ui-draggable');
      $(element).parent().parent().toggleClass('ui-draggable-handle');
    } else {
      $(element).draggable({
        zIndex: 999,
        revert: true,
        revertDuration: 10
      });
    }
  };

  /**
   * Méthode qui rend tous les fc-draggable draggable, prise en compte des differents span/champ si présence.
   */
  module.makeEventsDraggable = function () {
    var children = null;
    $('.fc-draggable').each(function () {
      // noinspection JSUnresolvedVariable
      children = this.children[0];
      var title = children.children[0] ? $.trim(children.children[0].innerHTML) : 'Erreur404';
      var trigrammeAuteur = trigrammeAuteurByHtml(children);
      var id = idByHtml(children);
      var client = clientByHtml(children);
      var couleurAgence = couleurAgenceByHtml(children);
      var dateRetour = dateRetourByHtml(children);
      var status = statusByHtml(children);
      var editable = editableByHtml(children);
      var nbQuartJournee = nbQuartJourneeByHtml(children);
      var start = startByHtml(children);
      var resource = resourceByHtml(children);
      var duration = module.calculQuartJournee(nbQuartJournee);
      var nbFils = 0 < $(this).parents('.calendar-day').length ? module.calculNbFilsOfPere(nbQuartJournee, start) : 0;

      $(this).addClass('event-' + couleurAgence);

      module.traitementDoublons(this, id);
      $(this).data('event', {
        id: id,
        title: title,
        stick: true,
        duration: duration,
        client: client,
        trigrammeAuteur: trigrammeAuteur,
        couleurAgence: couleurAgence,
        dateRetour: dateRetour,
        status: status,
        editable: editable,
        nbQuartJournee: nbQuartJournee,
        start: start,
        resourceId: resource,
        nbFils: nbFils
      });
      module.draggableEvent(this, editable);
    });
  };

  /**
   * Méthode qui rend un fc-draggable draggable, prise en compte des differents span/champ si présence.
   */
  module.makeOneEventDraggable = function (element) {
    var children = element.children[0];
    var title = $.trim(children.children[0].innerHTML);
    var trigrammeAuteur = trigrammeAuteurByHtml(children);
    var id = idByHtml(children);
    var client = clientByHtml(children);
    var couleurAgence = couleurAgenceByHtml(children);
    var dateRetour = dateRetourByHtml(children);
    var status = statusByHtml(children);
    var editable = editableByHtml(children);
    var nbQuartJournee = nbQuartJourneeByHtml(children);
    var start = startByHtml(children);
    var resource = resourceByHtml(children);
    var duration = module.calculQuartJournee(nbQuartJournee);
    var nbFils = 0 < $(element).parents('.calendar-day').length ? module.calculNbFilsOfPere(nbQuartJournee, start) : 0;

    $(element).addClass('event-' + couleurAgence);

    module.traitementDoublons(element, id);
    $(element).data('event', {
      id: id,
      title: title,
      stick: true,
      duration: duration,
      client: client,
      trigrammeAuteur: trigrammeAuteur,
      couleurAgence: couleurAgence,
      dateRetour: dateRetour,
      status: status,
      editable: editable,
      nbQuartJournee: nbQuartJournee,
      start: start,
      resourceId: resource,
      nbFils: nbFils
    });
    module.draggableEvent(element, editable);
  };

  /**
   * Recupere le numéro de l'id de la div du calendar.
   *
   * @param ID
   */
  module.getNumberIdDiv = function (ID) {
    return ID.replace(module.baseIdDivCalendar, '');
  };

  /**
   * Donne l'id d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {Number}
   */
  function idByHtml(children) {
    return children.children[8] ? $.trim(children.children[8].innerHTML) : ++lastAgendaItemId;
  }

  /**
   * Donne le trigramme d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {null}
   */
  function trigrammeAuteurByHtml(children) {
    return children.children[2] ? $.trim(children.children[2].innerHTML) : null;
  }

  /**
   * Donne le client d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {Number}
   */
  function clientByHtml(children) {
    return children.children[1] ? $.trim(children.children[1].innerHTML) : null;
  }

  /**
   * Donne la couelur d'agence d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {string}
   */
  function couleurAgenceByHtml(children) {
    return children.children[3] ? $.trim(children.children[3].innerHTML) : '#3a87ad';
  }

  /**
   * Donne la date de retour d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {null}
   */
  function dateRetourByHtml(children) {
    return children.children[4] ? $.trim(children.children[4].innerHTML) : null;
  }

  /**
   * Donne le status de l'event d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {null}
   */
  function statusByHtml(children) {
    return children.children[5] ? $.trim(children.children[5].innerHTML) : null;
  }

  /**
   * Donne si l'event est editable d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {string}
   */
  function editableByHtml(children) {
    return children.children[6] ? $.trim(children.children[6].innerHTML) : 'false';
  }

  /**
   * Donne le nb de quart de journée d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {*}
   */
  function nbQuartJourneeByHtml(children) {
    return children.children[7] ? parseInt(children.children[7].innerHTML, module.base) : 1;
  }

  /**
   * Donne la date de départ d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {null}
   */
  function startByHtml(children) {
    return children.children[9] ? $.trim(children.children[9].innerHTML) : null;
  }

  /**
   * Donne la ressource de l'event d'un fc-draggable en evoyent son fils. (objectif : réduire complexité méthode mere).
   * @param children
   *
   * @returns {null}
   */
  function resourceByHtml(children) {
    return children.children[10] ? $.trim(children.children[10].innerHTML) : null;
  }

  /**
   * Méthode qui crée un entête personnalisé.
   */
  module.makeEntete = function (concepteurJson) {
    var wrapperDiv = document.createElement('div');
    var div = document.createElement('div');
    var table = document.createElement('table');
    var thead = document.createElement('thead');
    var tr = document.createElement('tr');
    var td = document.createElement('td');
    var th = document.createElement('th');
    wrapperDiv.className = 'fc-view-container';
    div.className = 'fc-view fc-agendaDay-view fc-agenda-view';
    td.className = 'fc-head-container fc-widget-header';
    th.className = 'fc-axis fc-widget-header';
    wrapperDiv.appendChild(div);
    div.appendChild(table);
    table.appendChild(thead);
    thead.appendChild(tr);
    tr.appendChild(th);
    for (var k = 0; k < concepteurJson.length; k++) {
      th = document.createElement('th');
      th.className = 'fc-resource-cell';
      th.innerHTML = concepteurJson[k].title;
      tr.appendChild(th);
    }
    $('#en-tete').append(wrapperDiv);
  };

  /**
   * Retourne un id incrémenter pour les agendaItem (afin de creer un nouveau).
   */
  function getLastAgendaItem() {
    var id = 0;
    $.ajax({
      type: 'POST',
      async: false,
      url: Routing.generate('agenda.get.item.id')
    }).done(function (reponse) {
      // noinspection JSUnresolvedVariable
      id = reponse.lastId;
    });

    return id;
  }

  /**
   * Permet de persister les données.
   *
   * @param events
   */
  module.sendEvent = function (events) {
    for (var i = 0; i < events.length; i++) {
      delete events[i].source;
    }
    // console.log(events);
    $.ajax({
      type: 'POST',
      dataType: 'json',
      data: {'events': JSON.stringify(events)},
      url: Routing.generate('agenda.api.update')
    }).done(function (reponse) {
      // console.log(reponse);
    });
  };

  return module;
})();
