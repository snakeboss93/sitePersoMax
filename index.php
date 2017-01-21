<?php
header("X-XSS-Protection: 1");

use lib\core\Context;

//nom de l'application
$nameApp = 'src';

// Inclusion des classes et librairies
require_once 'lib/core.php';

// Ici on charge tous les controller de l'application pour suivre le modèle de conception MVC (Modele Vue Controller)
$allControllerClass = [];
foreach (glob($nameApp . '/controller/*.php') as $class) {
    include_once $class;
    $allControllerClass[] = $class;
}

// Action par défaut
$action = 'index';

// Ici on récupere l'action
if (array_key_exists('action', $_REQUEST)) {
    $action = $_REQUEST['action'];
}

// On instancie une session PHP
session_start();

// On récupère la classe Context qui nous permettra d'éxécuté les actions
$context = Context::getInstance();
$context->init($nameApp, $allControllerClass);

$view = $context->executeAction($action, $_REQUEST);

// Traitement des erreurs de bases, reste a traiter les erreurs d'inclusion
if (false === $view) {
    die('Une grave erreur s\'est produite, il est probable que l\'action ' . $action . ' n\'existe pas...');
} elseif (Context::SUCCESS === $view || Context::ERROR === $view) {
    // Inclusion du layout qui va lui meme inclure le template view
    $template_view = $nameApp . '/view/' . $view . '/' . $action . '.php';
    include($nameApp . '/view/' . $context->getLayout() . '.php');
} else {
    echo $view;
}
