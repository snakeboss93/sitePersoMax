/**
 * @author: Pierre PEREZ
 */
var bottom = (function () {
    'use strict';
    var module = {};

    module.spinner = '';
    var offsetAmis = 21;
    var offsetMessages = 21;
    var offsetProfil = 21;

    /**
     * methode public d'initialisation
     */
    module.init = function () {
        var content = document.createElement('div');
        var circle = document.createElement('div');
        var circle1 = document.createElement('div');
        content.className = 'content';
        circle.className = 'circle';
        circle1.className = 'circle1';
        content.appendChild(circle);
        content.appendChild(circle1);
        module.spinner = content;
        $('#spinner').append(module.spinner).hide();
        amis();
        messages();
        messagesProfil();
    };

    /**
     * data ajax pour les amies
     */
    function amis() {
        if (0 !== $('#liste-amis').length) {
            var antiDouble = 0;
            $(window).scroll(function () {
                var scrollHeight = parseInt($(document).height());
                var scrollPosition = parseInt($(window).height() + $(window).scrollTop());
                if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
                    if (antiDouble !== offsetAmis) {
                        antiDouble = offsetAmis;
                        $('#spinner').show();
                        $.ajax({
                            dataType: 'json',
                            url: '?action=loadAmis&offset=' + offsetAmis,
                            success: function (reponse) {
                                offsetAmis += 20;
                                if (0 !== reponse.data.amis.length) {
                                    structureAmi(reponse);
                                }
                            },
                            complete: function () {
                                $('#spinner').hide();
                            }
                        });
                    }
                }
            });
        }
    }

    /**
     * data ajax pour les messages
     */
    function messages() {
        if (0 !== $('#liste-messages').length) {
            var antiDouble = 0;
            $(window).scroll(function () {
                var scrollHeight = parseInt($(document).height());
                var scrollPosition = parseInt($(window).height() + $(window).scrollTop());
                if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
                    if (antiDouble !== offsetMessages) {
                        $('#spinner').show();
                        $.ajax({
                            dataType: 'json',
                            url: '?action=loadMessages&offset=' + offsetMessages,
                            success: function (reponse) {
                                offsetMessages += 20;
                                if (0 !== reponse.data.messages.length) {
                                    structureMessage(reponse);
                                }
                            },
                            complete: function () {
                                $('#spinner').hide();
                            }
                        });
                    }
                }
            });
        }
    }

    /**
     * data ajax pour les messages du profil
     */
    function messagesProfil() {
        if (0 !== $('#liste-messages-profil').length) {
            $(window).scroll(function () {
                var antiDouble = 0;
                var scrollHeight = parseInt($(document).height());
                var scrollPosition = parseInt($(window).height() + $(window).scrollTop());
                if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
                    if (antiDouble !== offsetProfil) {
                        var idProfil = parseInt($('#id-user').val());
                        $('#spinner').show();
                        $.ajax({
                            dataType: 'json',
                            url: '?action=loadMessagesByUser&offset=' + offsetProfil + "&id=" + idProfil,
                            success: function (reponse) {
                                offsetProfil += 20;
                                if (0 !== reponse.data.messages.length) {
                                    structureMessage(reponse);
                                }
                            },
                            complete: function () {
                                $('#spinner').hide();
                            }
                        });
                    }
                }
            });
        }
    }

    /**
     * structure html pour les amies
     * @param reponse
     */
    function structureAmi(reponse) {
        var ajouter = '';
        var tmp = '';
        for (var i = 0; i < reponse.data.amis.length; i++) {
            tmp = JSON.parse(reponse.data.amis[i]);
            if (null !== tmp) {
                ajouter = '';
                ajouter = "<a href='?action=profil&id=" + tmp.id + "'"
                    + "title='vers profil de " + tmp.prenom + " " + tmp.nom + "'>"
                    + "<div class='well'>"
                    + "<div class='row'>"
                    + "<div class='col-md-2 hidden-xs'>"
                    + tools.structureAvatar(tmp.avatar, 50)
                    + "</div>"
                    + "<div class='col-md-10 col-xs-12'>"
                    + "<p class='flow-text'>"
                    + tmp.prenom + " " + tmp.nom
                    + "</p>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                    + "</a>";
                $('#principale-well').append(ajouter);
            }
        }
    }

    /**
     * strucuter html pour les messages
     * @param reponse
     */
    function structureMessage(reponse) {
        var ajouter = '';
        var tmp = '';
        for (var i = 0; i < reponse.data.messages.length; i++) {
            tmp = JSON.parse(reponse.data.messages[i]);
            if (null !== tmp) {
                null !== tmp.destinataire ? tmp.destinataire = JSON.parse(tmp.destinataire) : tmp.destinataire;
                null !== tmp.emetteur ? tmp.emetteur = JSON.parse(tmp.emetteur) : tmp.emetteur;
                null !== tmp.parent ? tmp.parent = JSON.parse(tmp.parent) : tmp.parent;
                null !== tmp.post ? tmp.post = JSON.parse(tmp.post) : tmp.post;
                ajouter = '';
                ajouter = "<div class='well'>"
                    + "<div class='row'>"
                    + "<div class='col-md-2 hidden-xs'>"
                    + tools.structureAvatar(tmp.emetteur.avatar, 100)
                    + "</div>"
                    + "<div class='col-md-10 col-xs-12'>"
                    + "<span class='full-name'>"
                    + "<a href='?action=profil&id=" + tmp.emetteur.id + "'"
                    + "title='vers profil de " + tmp.emetteur.prenom + ' ' + tmp.emetteur.nom + "'>"
                    + tmp.emetteur.prenom + ' ' + tmp.emetteur.nom
                    + "</a>"
                    + "</span>"
                    + "<span class='date'>" + tmp.post.date.date + "</span>";
                if (null !== tmp.destinataire && tmp.destinataire.id !== tmp.emetteur.id) {
                    ajouter += "<span class='destination'>Sur le mur de "
                        + "<a href='?action=profil&id=" + tmp.destinataire.id + "'"
                        + "title='vers profil de " + tmp.destinataire.prenom + ' ' + tmp.destinataire.nom + "'>"
                        + tmp.destinataire.prenom + ' ' + tmp.destinataire.nom
                        + "</a>"
                        + "</span>";
                }
                if (null !== tmp.parent) {
                    ajouter += "<span class='partage-de'>A partager de "
                        + "<a href='?action=profil&id=" + tmp.parent.id + "'"
                        + "title='vers profil de " + tmp.parent.prenom + ' ' + tmp.parent.nom + "'>"
                        + tmp.parent.prenom + ' ' + tmp.parent.nom
                        + "</a>"
                        + "</span>";
                }
                ajouter += "<p class='flow-text'>" + tmp.post.texte.sanitize() + "</p>";
                if (null !== tools.structureImgPost(tmp.post.image)) {
                    ajouter += tools.structureImgPost(tmp.post.image);
                }
                ajouter += "<span id='like-" + tmp.post.id + "' class='like'>"
                    + tmp.aime + " "
                    + "<i class='fa fa-heart' aria-hidden='true'></i>"
                    + "</span>"
                    + "<span id='partage-" + tmp.post.id + "' class='partage'>"
                    + "<i class='fa fa-share' aria-hidden='true'></i>"
                    + "Partager" + " "
                    + "</span>"
                    + "</div>"
                    + "</div>"
                    + "</div>";

                $('#principale-well').append(ajouter);
            }
        }
    }

    return module;
})();
