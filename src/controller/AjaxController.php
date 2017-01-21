<?php

namespace maxime\controller;

use lib\core\Context;

/**
 * Class AjaxController
 */
class AjaxController
{
    const LIMIT = 20;

    /**
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function uploadImg($request, $context)
    {
        array_key_exists('avatar', $request) ? $rep = 'avatar' : $rep = 'post';
        $infosFichier = pathinfo($_FILES['file']['name']);
        $extensionUpload = $infosFichier['extension'];
        if (null !== MainController::addImage($rep, $_FILES['file'], $tmp = time())) {
            $context->__set('imgname', $rep.$tmp.'.'.$extensionUpload);

            return $context->jsonSerialize();
        }
        $context->__set('imgname', '');

        return $context->jsonSerialize();
    }
}
