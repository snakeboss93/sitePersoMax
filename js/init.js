/**
 * Methode pour enlever les balise script dans les message coté client ...
 * @returns {String}
 */
String.prototype.sanitize = function () {
    var str = this;
    str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
    str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
    return str;
};

/**
 * Pour instancier nous même les dropzone.
 * @type {boolean}
 */
Dropzone.autoDiscover = false;

/**
 * initialise l'application
 */
$(document).ready(function () {
    connection.init();
    notification.init();
    menu.init();
    menu.initMobile();
    bottom.init();
    monDropzone.init();
    send.init();
    chat.init();
    likeShare.init();
    editionProfil.init();
});
