<?php

namespace ifacebook\controller;

use ifacebook\model\Chat\Chat;
use ifacebook\model\Chat\ChatManager;
use ifacebook\model\Message\Message;
use ifacebook\model\Message\MessageManager;
use ifacebook\model\Post\Post;
use ifacebook\model\Post\PostManager;
use ifacebook\model\Utilisateur\UtilisateurManager;
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
    public function logoutAjax($request, $context)
    {
        session_destroy();
        $context->setNotification('Vous êtes bien déconnecté');

        return $context->jsonSerialize();
    }

    /**
     * @author: Loic TORO
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function sendChatAjax($request, $context)
    {
        if (array_key_exists('txt', $request) && '' !== $request['txt']) {
            $utilsateurManager = new UtilisateurManager();
            $chatManager = new ChatManager();
            $postManager = new PostManager();
            $chat = new Chat();
            $post = new Post();
            $post->setDate(new \DateTime());
            $post->setTexte($request['txt']);
            $chat->setEmetteur($utilsateurManager->findOneById($context::getSessionAttribute('id')));
            $chat->setPost($post);
            $postManager->save($post);
            $chatManager->save($chat);
            $context->__set('chats', $chat);
        }

        return $context->jsonSerialize();
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function sendMessage($request, $context)
    {
        if (array_key_exists('txt', $request) && '' !== $request['txt']) {
            $utilsateurManager = new UtilisateurManager();
            $messageManager = new MessageManager();
            $postManager = new PostManager();
            $message = new Message();
            $post = new Post();
            $post->setDate(new \DateTime());
            $post->setTexte(array_key_exists('txt', $request) ? $request['txt'] : 0);
            $post->setImage(
                array_key_exists(
                    'img',
                    $request
                ) && '' !== $request['img'] ? 'https://pedago.univ-avignon.fr/~uapv1400638/ifacebook/images/post/'.$request['img'] : null
            );
            $message->setEmetteur($utilsateurManager->findOneById($context::getSessionAttribute('id')));
            $message->setDestinataire(
                array_key_exists('to', $request) ? $utilsateurManager->findOneById((int)$request['to']) : 0
            );
            $message->setParent($utilsateurManager->findOneById($context::getSessionAttribute('id')));
            $message->setAime(0);
            $message->setPost($post);
            $postManager->save($post);
            $messageManager->save($message);

            $context->__set('message', $message);
            $context->setNotification('Message envoyé !');
        }

        return $context->jsonSerialize();
    }

    /**
     * @author: Loic TORO
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function loadChatAjax($request, $context)
    {
        /** @var ChatManager $chatRepo */
        $chatRepo = new ChatManager();
        $context->__set(
            'chats',
            $chatRepo->findAll(
                5,
                array_key_exists('offset', $request) ? (int)$request['offset'] : null
            )
        );

        return $context->jsonSerialize();
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function loadAmis($request, $context)
    {
        /** @var UtilisateurManager $utilisateurManager */
        $utilisateurManager = new UtilisateurManager();
        $context->__set(
            'amis',
            $utilisateurManager->findAll(
                self::LIMIT,
                array_key_exists('offset', $request) ? (int)$request['offset'] : null
            )
        );

        return $context->jsonSerialize();
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function loadMessages($request, $context)
    {
        /** @var MessageManager $messageManager */
        $messageManager = new MessageManager();
        $context->__set(
            'messages',
            $messageManager->findAll(
                self::LIMIT,
                array_key_exists('offset', $request) ? (int)$request['offset'] : null
            )
        );

        return $context->jsonSerialize();
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function loadMessagesByUser($request, $context)
    {
        /** @var MessageManager $messageManager */
        $messageManager = new MessageManager();
        $context->__set(
            'messages',
            $messageManager->findByEmetteurId(
                array_key_exists('id', $request) ? (int)$request['id'] : 0,
                self::LIMIT,
                array_key_exists('offset', $request) ? (int)$request['offset'] : null
            )
        );

        return $context->jsonSerialize();
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function addLike($request, $context)
    {
        if (array_key_exists('id', $request)) {
            /** @var MessageManager $messageManager */
            $messageManager = new MessageManager();
            /** @var Message $message */
            $message = $messageManager->findOneById((int)$request['id']);
            $message->setAime($message->getAime() + 1);
            $messageManager->update($message);
            $context->__set('like', $message->getAime());
        }

        return $context->jsonSerialize();
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function addShare($request, $context)
    {
        if (array_key_exists('id', $request) && array_key_exists('idUser', $request)) {
            /** @var MessageManager $messageManager */
            $messageManager = new MessageManager();
            /** @var UtilisateurManager $utilisateurManager */
            $utilisateurManager = new UtilisateurManager();
            /** @var Message $message */
            $message = $messageManager->recopieConstruct(
                $messageManager->findOneById((int)$request['id']),
                $utilisateurManager->findOneById((int)$request['idUser'])
            );

            $messageManager->save($message);
            $context->setNotification('Message partagé.');
        }

        return $context->jsonSerialize();
    }

    /**
     * @author: Pierre PEREZ
     *
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

    /**
     * @author: Loic TORO
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function editionProfil($request, $context)
    {
        if (array_key_exists('by', $request) && $context::getSessionAttribute('id') === (int)$request['by']) {
            $utilisateurManager = new UtilisateurManager();
            $user = $utilisateurManager->findOneById($context::getSessionAttribute('id'));
            array_key_exists(
                'status',
                $request
            ) && '' !== $request['status'] ? $status = $request['status'] : $status = null;
            array_key_exists(
                'avatar',
                $request
            ) && '' !== $request['avatar'] ? $avatar = 'https://pedago.univ-avignon.fr/~uapv1400638/ifacebook/images/avatar/'.$request['avatar'] : $avatar = null;
            if (null !== $avatar) {
                $user->setAvatar($avatar);
                $context->__set(
                    'avatar',
                    $avatar
                );
            }
            if (null !== $status) {
                $user->setStatut($status);
                $context->__set('status', $status);
            }
            $utilisateurManager->update($user);
        }

        return $context->jsonSerialize();
    }

    /**
     * @author: Pierre PEREZ
     *
     * @param $request
     * @param Context $context
     *
     * @return mixed
     */
    public function searchUser($request, $context)
    {
        /** @var UtilisateurManager $utilisateurManager */
        $utilisateurManager = new UtilisateurManager();

        return json_encode(
            $utilisateurManager->findByNameOrLastName(array_key_exists('q', $request) ? $request['q'] : 0)
        );
    }
}
