/**

 */
var bottom = (function () {
    'use strict';
    var module = {};

    var offsetMessages = 21;

    /**
     * methode public d'initialisation
     */
    module.init = function () {
        messages();
    };


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
