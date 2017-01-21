/* exported ArobanDragndrop */
var ArobanDragndrop = (function () {
    'use strict';
    var module = {};
    var paramName = "file"; // The name that will be used to transfer the file
    var thumbnailWidth = 400;
    var thumbnailHeight = 257;
    // var previewTemplate = document.getElementById('preview-template').innerHTML;
    var autoProcessQueue = false;
    var dictDefaultMessage = "Glissez vos fichiers ici.";
    var dictFallbackMessage = "Votre navigateur ne supporte pas la fonctionnalité de glisser-déposer pour l'envoi de fichiers.";
    var dictFallbackText = "Veuillez utiliser le champ fichier pour télécharger vos documents.";
    var dictInvalidFileType = "Ce type de fichier n'est pas autorisé.";
    var dictFileTooBig = "Votre fichier est trop volumineux.";
    var dictResponseError = "Une erreur est survenue.";
    var addRemoveLinks = false;
    var dictCancelUpload = "Ce fichier va être supprimé.";
    var dictCancelUploadConfirmation = "Voulez vous vraiment retirer ce fichier ?";
    var dictRemoveFile = "Supprimer";
    var dictMaxFilesExceeded = "Vous essayez d'envoyer trop de fichiers en même temps.";

    /**
     * Définir la dropzone.
     *
     * @param dropzoneParams
     */
    module.defineDropzone = function(dropzoneParams) {
        var footerId = 'footer-fixed-'+dropzoneParams.id;
        var submitId = 'submit-'+dropzoneParams.id;

        window.Dropzone.options[dropzoneParams.id] = {
            url: dropzoneParams.urlForUpload,
            paramName: paramName,
            maxFilesize: dropzoneParams.maxFilesize,
            maxFiles: dropzoneParams.maxFiles,
            parallelUploads: dropzoneParams.maxFiles,
            thumbnailWidth: thumbnailWidth,
            thumbnailHeight: thumbnailHeight,
            // previewTemplate: previewTemplate,
            autoProcessQueue: autoProcessQueue,
            dictDefaultMessage: dictDefaultMessage,
            dictFallbackMessage: dictFallbackMessage,
            dictFallbackText: dictFallbackText,
            dictInvalidFileType: dictInvalidFileType,
            dictFileTooBig: dictFileTooBig,
            dictResponseError: dictResponseError,
            addRemoveLinks: addRemoveLinks,
            dictCancelUpload: dictCancelUpload,
            dictCancelUploadConfirmation: dictCancelUploadConfirmation,
            dictRemoveFile: dictRemoveFile,
            dictMaxFilesExceeded: dictMaxFilesExceeded,
            init: function() {
                var myDropzone = this;
                this.on("processing", function() {
                    this.options.url = dropzoneParams.urlForUpload;
                });
                this.on("addedfile", function() {
                    $('#'+footerId).show();
                });
                this.on("queuecomplete", function() {
                    if (typeof dropzoneParams.urlRedirect !== "undefined") {
                        window.location.href = dropzoneParams.urlRedirect;
                    }
                });
                this.on("maxfilesreached", function () {
                    myDropzone.reset();
                });
                $('#'+submitId).on("click", function() {
                    myDropzone.processQueue();
                });
            }
        };
    };

    return module;
})();

