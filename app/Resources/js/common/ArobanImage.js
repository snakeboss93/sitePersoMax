/* exported ArobanImage */
var ArobanImage = (function () {
    'use strict';
    var module = {};

    var setSelectWidth;
    var setSelectHeight;
    var boxWidth;
    var boxHeight;
    var ratio;
    var image = {};

    /**
     * URL de l'image
     * @param value
     */
    module.setImageUrl = function (value) {
        image.url = value;
    };

    /**
     * @param value
     */
    module.setImageWidth = function (value) {
        image.width = value;
    };

    /**
     * @param value
     */
    module.setImageHeight = function (value) {
        image.height = value;
    };

    /**
     * Config.
     * @param selectWidth
     * @param selectHeight
     * @param boxW
     * @param boxH
     * @param ratioImage
     */
    module.setConfig = function (selectWidth, selectHeight, boxW, boxH, ratioImage) {
        setSelectWidth = selectWidth;
        setSelectHeight = selectHeight;
        boxWidth = boxW;
        boxHeight = boxH;
        ratio = ratioImage;
    };

    /**
     * @param left
     * @param top
     * @param width
     * @param height
     */
    module.setupDefault = function (left, top, width, height) {
        $('#form_left').val(left);
        $('#form_top').val(top);
        $('#form_width').val(width);
        $('#form_height').val(height);
    };

    /**
     * @param c
     */
    module.showCoords = function (c) {
        $('#form_left').val(c.x);
        $('#form_top').val(c.y);
        $('#form_width').val(c.w);
        $('#form_height').val(c.h);
    };

    /**
     * Initialiser
     */
    module.init = function () {
        module.setupDefault(0, 0, image.width, image.height);

        if (image.width < boxWidth) {
            boxWidth = image.width;
            boxHeight = image.height;
        }

        if (image.width < setSelectWidth) {
            setSelectWidth = image.width;
            setSelectHeight = image.width / ratio;
        }
    };

    /**
     * Initialisation de JCrop.
     */
    module.jcrop = function (selector) {
        $(selector).Jcrop({
            onSelect: module.showCoords,
            bgColor: 'black',
            bgOpacity: .6,
            setSelect: [0, 0, setSelectWidth, setSelectHeight],
            aspectRatio: ratio,
            boxWidth: boxWidth,
            boxHeight: boxHeight,
            trueSize: [image.width, image.height]
        });
    };

    return module;
})();

