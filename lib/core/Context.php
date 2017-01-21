<?php

namespace lib\core;

use JsonSerializable;

/**
 * Class Context.
 */
class Context implements JsonSerializable
{
    const SUCCESS = 'Success';
    const ERROR = 'Error';
    const NONE = 'None';
    /** @var self $instance */
    private static $instance = null;

    /** @var array $data */
    private $data;
    /** @var  string $name */
    private $name;
    /** @var string $layout */
    protected $layout;
    /** @var array $class */
    protected $class;
    /** @var string $notification */
    protected $notification;

    /**
     * @return Context
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new Context();
        }

        return self::$instance;
    }

    /**
     * Context constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param string $name
     * @param array $class
     */
    public function init($name, $class)
    {
        $this->name = $name;
        $this->class = $this->sanitizeClassName($class);
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @param string $url
     */
    public function redirect($url)
    {
        header('location:'.$url);
    }

    /**
     * @param string $action
     * @param $request
     * @return bool
     */
    public function executeAction($action, $request)
    {
        $this->setLayout('layout/layout');
        foreach ($this->class as $class) {
            if (method_exists($class, $action)) {
                return $class::$action($request, $this);
            }
        }

        return false;
    }

    /**
     * @param string $attribute
     * @return null
     */
    public static function getSessionAttribute($attribute)
    {
        if (array_key_exists($attribute, $_SESSION)) {
            return $_SESSION[$attribute];
        } else {
            return null;
        }
    }

    /**
     * @param string $attribute
     * @param $value
     */
    public static function setSessionAttribute($attribute, $value)
    {
        $_SESSION[$attribute] = $value;
    }

    /**
     * @param $prop
     * @return null
     */
    public function __get($prop)
    {
        if (array_key_exists($prop, $this->data)) {
            return $this->data[$prop];
        } else {
            return null;
        }
    }

    /**
     * @param $prop
     * @param $value
     */
    public function __set($prop, $value)
    {
        $this->data[$prop] = $value;
    }

    /**
     * @return string
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param string $notification
     * @return Context
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Permet de nettoyer la chaine des controllers pour avoir les 'namespace' de class.
     *
     * @param $allControllerClass
     * @return mixed
     */
    private function sanitizeClassName($allControllerClass)
    {
        $allControllerClass = str_replace('/', '\\', $allControllerClass);

        return str_replace('.php', '', $allControllerClass);
    }

    /**
     * Permet de sérialiser notre objet Context en json.
     * Utilisé pour les requete ajax.
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return json_encode($this->arraySerialize());
    }

    /**
     * Permet de sérialiser notre objet Context en array.
     *
     * @return array
     */
    protected function arraySerialize()
    {
        return get_object_vars($this);
    }
}
