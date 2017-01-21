<?php

namespace maxime\controller;

use maxime\model\Utilisateur\Utilisateur;
use maxime\model\Utilisateur\UtilisateurManager;
use lib\core\Context;

/**
 * Class MainController
 */
class MainController
{
    const LIMIT = 20;

    /**
     * @param $request
     * @param Context $context
     *
     * @return string
     */
    public static function login($request, $context)
    {
        if (array_key_exists('form', $request)) {
            $login = $request['form']['login'];
            $pass = $request['form']['pass'];
            /** @var UtilisateurManager $userManager */
            $userManager = new UtilisateurManager();
            /** @var Utilisateur $user */
            $user = $userManager->findConnection($login, $pass);
            if (false === $user) {
                return Context::ERROR;
            }
            Context::setSessionAttribute('id', $user->getId());

            $context->redirect('?action=index');

            return Context::SUCCESS;
        }

        return Context::SUCCESS;
    }


    /**
     * @param $rep
     * @param $file
     * @param null $time
     *
     * @return string
     */
    public static function addImage($rep, $file, $time = null)
    {
        $image = 'images/'.$rep.'/';
        $folder = 'images/'.$rep.'/';
        if (null !== $file && 0 === $file['error']) {
            null === $time ? $tmp = time() : $tmp = $time;
            $infosFichier = pathinfo($file['name']);
            $extensionUpload = $infosFichier['extension'];
            $extensionsAutorisees = ['jpg', 'jpeg', 'gif', 'png'];
            if (in_array($extensionUpload, $extensionsAutorisees)) {
                $image .= $rep.$tmp.'.'.$extensionUpload;
                move_uploaded_file($file['tmp_name'], $folder.$rep.$tmp.'.'.$extensionUpload);

                return $image;
            }
        }

        return null;
    }
}
