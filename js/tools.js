/**
 * @author: Pierre PEREZ
 */
var tools = (function () {
    'use strict';
    var module = {};

    /**
     * Fabrication de la structure d'une image d'avatar.
     * @param avatar
     * @param taille
     * @returns {*}
     */
    module.structureAvatar = function (avatar, taille) {
        if (null !== avatar && '' !== avatar) {
            return "<img src='" + avatar + "' alt='avatar' width='" + taille + "' height='" + taille + "'/>";
        } else {
            return "<img src='images/avatar/defaut.png' alt='avatar par default' width='" + taille + "' height='" + taille + "'/>";
        }
    };

    /**
     * Fabrication de la structure d'une image de post.
     * @param imgPost
     * @returns {*}
     */
    module.structureImgPost = function (imgPost) {
        if (null !== imgPost && '' !== imgPost) {
            return "<img src='" + imgPost + "' alt='image de post' class='img-responsive max-img'/>";
        }
        return null;
    };

    /**
     * Permet de masquer la zone de notification.
     */
    module.hideNotif = function () {
        $('#notification').html('');
    };

    return module;
})();
