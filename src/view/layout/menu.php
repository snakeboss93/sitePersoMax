<?php
/** @var \ifacebook\model\Utilisateur\Utilisateur $moi */
$moi = $context->__get('moi');
?>
<div id="menu">

    <select id="search-user" class="hidden-xs">
        <option value="" selected="selected">Rechercher un ami</option>
    </select>

    <div class="card">
        <div class="card-image">
            <img src='<?php echo $moi->getAvatar(); ?>' class="img-responsive img-profil" alt='avatar'
                 id="menu-avatar"/>
            <span class="card-title flow-text"><?php echo $moi->getFullName(); ?></span>
            <span class="hidden" id="menu-user-id"><?php echo $moi->getId(); ?></span>
        </div>
    </div>

    <ul>
        <li>
            <a href="?action=mur">
                <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
                Mur
            </a>
        </li>
        <li>
            <a href="?action=profil">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                Mon profil
            </a>
        </li>
        <li>
            <a href="?action=amis">
                <span><i class="fa fa-users" aria-hidden="true"></i></span>
                Mes amis
            </a>
        </li>
        <li class="hidden-xs">
            <a href="javascript:void(0)" id="chat">
                <span><i class="fa fa-weixin" aria-hidden="true"></i></span>
                Chat
                <span id="chat-badge" class="badge"></span>
            </a>
        </li>
        <li class="text-center" id="deconnection">
            <span class="glyphicon glyphicon-off text-danger" aria-hidden="true"></span>
        </li>
    </ul>
</div>
