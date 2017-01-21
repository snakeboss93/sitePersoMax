/**
 * @author: Loic TORO
 */
var chat = (function () {
    'use strict';
    var module = {};

    module.id = '';
    var offset = 0;
    var lastChatId = 0;

    /**
     * initialisation de la modal de chat.
     */
    module.init = function () {
        $('#chatModal').dialog({
            title: "Chat",
            width: 700,
            height: 555,
            autoOpen: false,
            dialogClass: 'toMinimize'
        });
        miniAndMaxiWork();
        sendMessageModal();
        var ini = false;
        $("#chat").click(function () {
            $("#chatModal").dialog("open");
            false === ini ? chargementFirstData() : null;
            false === ini ? chargementDataNew() : null;
            chargementData();
            $('#chat-badge').html(null);
            $('.ui-widget-header').css('background-color', '#e9e9e9');
            ini = true;
        });
        $("#chatModal").click(function () {
            $('#chat-badge').html(null);
            $('.ui-widget-header').css('background-color', '#e9e9e9');
        });
    };

    /**
     * permet de minimiser ou maximiser la fenetre dfe chat
     */
    function miniAndMaxiWork() {
        $(".toMinimize").children(".ui-dialog-titlebar")
            .append(
                "<button type='button' id='icon-reduce' class='ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close' title='min-max'>"
                + "<span class='ui-button-icon ui-icon ui-icon-minus'></span>"
                + "<span class='ui-button-icon-space'></span>Minimiser / Maximiser</button>"
            );
        $("#icon-reduce").click(function () {
            if ($(this).hasClass('maxi')) {
                $('#chatModal').parents('.ui-dialog').animate({
                    top: ($(window).height() - 555) / 2,
                    height: 555
                }, 200);
                $('#chatModal').show();
                $(this).removeClass('maxi');
            } else {
                $(this).parents('.ui-dialog').animate({
                    height: '40px',
                    top: $(window).height() - 40
                }, 200);
                $('#chatModal').hide();
                $(this).addClass('maxi');
            }
        });
    }

    /**
     * déclaration 'privée'
     * chargement des données
     */
    function chargementFirstData() {
        $.ajax({
            dataType: 'json',
            url: '?action=loadChatAjax',
            success: function (reponse) {
                if (0 !== reponse.data.chats.length) {
                    var tmp = '';
                    for (var j = 0; j < reponse.data.chats.length; j++) {
                        tmp = JSON.parse(reponse.data.chats[j]);
                        if (lastChatId < tmp.id) {
                            lastChatId = tmp.id;
                        }
                    }
                    structureChats(reponse);
                }
            }
        });
    }

    /**
     * déclaration 'privée'
     * chargement des données
     */
    function chargementData() {
        $('#chat-contenu').bind('scroll', function () {
            if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                $.ajax({
                    dataType: 'json',
                    url: '?action=loadChatAjax&offset=' + offset,
                    success: function (reponse) {
                        if (0 !== reponse.data.chats.length) {
                            var tmp = '';
                            for (var j = 0; j < reponse.data.chats.length; j++) {
                                tmp = JSON.parse(reponse.data.chats[j]);
                                if (lastChatId < tmp.id) {
                                    lastChatId = tmp.id;
                                }
                            }
                            structureChats(reponse);
                            offset += 5;
                        }
                    }
                });
            }
        });
    }

    /**
     * chargement des données nouvelle
     */
    function chargementDataNew() {
        if (window.Worker) {
            var worker = new Worker('js/worker/sw.js');
            worker.onmessage = function (event) {
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: '?action=loadChatAjax',
                    success: function (reponse) {
                        var tmp = '';
                        var compteur = 0;
                        var idSend = 0;
                        $('.send').each(function () {
                            idSend < parseInt($(this).html()) ? idSend = parseInt($(this).html()) : null;
                        });
                        for (var j = 0; j < reponse.data.chats.length; j++) {
                            tmp = JSON.parse(reponse.data.chats[j]);
                            if (lastChatId < tmp.id) {
                                if (tmp.id !== idSend) {
                                    structureChatByJSON(tmp);
                                    lastChatId = tmp.id;
                                    compteur++;
                                }
                            }
                        }
                        if (0 !== compteur) {
                            notification.spawnNotification('Vous avez ' + compteur + ' nouveau(x) message(s)', 'ifacebook');
                            $('#chat-badge').html(compteur);
                            $('.ui-widget-header').css('background-color', '#FFA500');
                        }
                        worker.postMessage('ping');
                    }
                });
            };
            worker.postMessage('ping');
        } else {
            alert("Désolé votre navigateur ne supporte pas les workers !");
        }
    }

    /**
     * permet d'envoyer un message de chat
     */
    function sendMessageModal() {
        $('#chat-submit').click(function () {
            $.ajax({
                dataType: 'json',
                data: {txt: $.trim($('#form_message').val())},
                url: '?action=sendChatAjax',
                success: function (reponse) {
                    $('#form_message').val('');
                    structureChat(reponse);
                }
            });
        });
    }

    /**
     * structure html des messages chat
     */
    function structureChats(reponse) {
        var ajouter = '';
        var tmp = '';
        for (var i = (reponse.data.chats.length - 1); i > -1; i--) {
            tmp = JSON.parse(reponse.data.chats[i]);
            null !== tmp.emetteur ? tmp.emetteur = JSON.parse(tmp.emetteur) : tmp.emetteur;
            null !== tmp.post ? tmp.post = JSON.parse(tmp.post) : tmp.post;
            if (null === tmp.emetteur) {
                continue;
            }
            if (null !== tmp) {
                ajouter = '';
                ajouter = "<div class='well'>"
                    + "<div class='row'>"
                    + "<div class='col-md-2 hidden-xs'>"
                    + tools.structureAvatar(tmp.emetteur.avatar, 50)
                    + "</div>"
                    + "<div class='col-md-4 col-xs-6'>"
                    + "<p class='flow-text'>"
                    + tmp.emetteur.prenom + " " + tmp.emetteur.nom
                    + "</p>"
                    + "</div>"
                    + "<div class='col-md-6 col-xs-6'>"
                    + "<p class='flow-text'>" + tmp.post.texte.sanitize() + "</p>"
                    + "</div>"
                    + "</div>"
                    + "</div>";
                if (0 !== $('#chat-contenu-mobile').length) {
                    $('#chat-contenu-mobile').prepend(ajouter);
                } else {
                    $('#chat-contenu').prepend(ajouter);
                }
            }
        }
    }

    /**
     * structure html des messages chat
     */
    function structureChat(reponse) {
        var ajouter = '';
        var tmp = JSON.parse(reponse.data.chats);
        null !== tmp.emetteur ? tmp.emetteur = JSON.parse(tmp.emetteur) : tmp.emetteur;
        null !== tmp.post ? tmp.post = JSON.parse(tmp.post) : tmp.post;
        if (null !== tmp) {
            ajouter = "<div class='well'>"
                + "<div class='row'>"
                + "<div class='col-md-2 hidden-xs'>"
                + tools.structureAvatar(tmp.emetteur.avatar, 50)
                + "</div>"
                + "<div class='col-md-4 col-xs-6'>"
                + "<p class='flow-text'>"
                + "<span class='send hidden'>" + tmp.id + "</span>"
                + tmp.emetteur.prenom + " " + tmp.emetteur.nom
                + "</span>"
                + "</div>"
                + "<div class='col-md-6 col-xs-6'>"
                + "<p class='flow-text'>" + tmp.post.texte.sanitize() + "</p>"
                + "</div>"
                + "</div>"
                + "</div>";
            if (0 !== $('#chat-contenu-mobile').length) {
                $('#receive-ajax-chat-mobile').prepend(ajouter);
            } else {
                $('#receive-ajax-chat').prepend(ajouter);
            }
        }
    }

    /**
     * structure html des messages chat avec un JSON
     */
    function structureChatByJSON(tmp) {
        var ajouter = '';
        null !== tmp.emetteur ? tmp.emetteur = JSON.parse(tmp.emetteur) : tmp.emetteur;
        null !== tmp.post ? tmp.post = JSON.parse(tmp.post) : tmp.post;
        if (null !== tmp) {
            ajouter = "<div class='well'>"
                + "<div class='row'>"
                + "<div class='col-md-2 hidden-xs'>"
                + tools.structureAvatar(tmp.emetteur.avatar, 50)
                + "</div>"
                + "<div class='col-md-4 col-xs-6'>"
                + "<p class='flow-text'>"
                + tmp.emetteur.prenom + " " + tmp.emetteur.nom
                + "</p>"
                + "</div>"
                + "<div class='col-md-6 col-xs-6'>"
                + "<p class='flow-text'>" + tmp.post.texte.sanitize() + "</p>"
                + "</div>"
                + "</div>"
                + "</div>";
            if (0 !== $('#chat-contenu-mobile').length) {
                $('#receive-ajax-chat-mobile').prepend(ajouter);
            } else {
                $('#receive-ajax-chat').prepend(ajouter);
            }
        }
    }

    return module;
})
();
