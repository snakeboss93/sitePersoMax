<?php

namespace ifacebook\controller;

use ifacebook\model\Message\MessageManager;
use ifacebook\model\Utilisateur\Utilisateur;
use ifacebook\model\Utilisateur\UtilisateurManager;
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
            $user = $userManager->getUserByLoginAndPass($login, $pass);
            if (false === $user) {
                $context->setNotification('Vérifié votre mot de passe et Login');

                return Context::ERROR;
            }
            $context->setNotification('Bonjour '.$user->getPrenom());
            Context::setSessionAttribute('id', $user->getId());

            $context->redirect('?action=index');

            return Context::SUCCESS;
        }

        return Context::SUCCESS;
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param Context $context
     */
    public static function toMenu($context)
    {
        if ($context::getSessionAttribute('id')) {
            /** @var UtilisateurManager $utilisateurManager */
            $utilisateurManager = new UtilisateurManager();
            $context->__set('moi', $utilisateurManager->findOneById($context::getSessionAttribute('id')));
        }
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return string
     */
    public static function mur($request, $context)
    {
        if ($context::getSessionAttribute('id')) {
            self::toMenu($context);
            /** @var MessageManager $messageManager */
            $messageManager = new MessageManager();
            /** @var UtilisateurManager $utilisateurManager */
            $utilisateurManager = new UtilisateurManager();

            $context->__set('data', $messageManager->findAll(self::LIMIT));
            $context->__set('user', $utilisateurManager->findOneById($context::getSessionAttribute('id')));

            return Context::SUCCESS;
        }

        return Context::ERROR;
    }

    /**
     * Equivaut a mon mur personnel du TP.
     *
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return string
     */
    public static function profil($request, $context)
    {
        if ($context::getSessionAttribute('id')) {
            self::toMenu($context);
            /** @var UtilisateurManager $utilisateurManager */
            $utilisateurManager = new UtilisateurManager();
            /** @var MessageManager $messageManager */
            $messageManager = new MessageManager();

            if (array_key_exists('id', $request)) {
                $context->__set('data', $messageManager->findByEmetteurId($request['id'], self::LIMIT));
                $context->__set('user', $utilisateurManager->findOneById($request['id']));

                return Context::SUCCESS;
            } else {
                $context->__set(
                    'data',
                    $messageManager->findByEmetteurId($context::getSessionAttribute('id'), self::LIMIT)
                );
                $context->__set('user', $utilisateurManager->findOneById($context::getSessionAttribute('id')));

                return Context::SUCCESS;
            }

        }

        return Context::ERROR;
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return string
     */
    public static function amis($request, $context)
    {
        if ($context::getSessionAttribute('id')) {
            self::toMenu($context);
            /** @var UtilisateurManager $utilisateurManager */
            $utilisateurManager = new UtilisateurManager();

            $context->__set('data', $utilisateurManager->findAll(self::LIMIT));

            return Context::SUCCESS;
        }

        return Context::ERROR;
    }

    /**
     * @author Loic TORO
     *
     * @param $rep
     * @param $file
     * @param null $time
     *
     * @return string
     */
    public static function addImage($rep, $file, $time = null)
    {
        $image = 'https://pedago.univ-avignon.fr/~uapv1400638/ifacebook/images/'.$rep.'/';
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
