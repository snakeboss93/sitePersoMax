<?php
/** @var \ifacebook\model\Utilisateur\Utilisateur $user */
$user = $context->__get('user');
if ($context::getSessionAttribute('id') === $user->getId()) { ?>
    <h2 id="liste-messages-profil">Mon profil</h2>
<?php } else { ?>
    <h2 id="liste-messages-profil">Profil de <?php echo $user->getFullName(); ?></h2>
<?php } ?>
<div class="well">
    <div class="row">
        <div class="col-md-2 hidden-xs">
            <img id="profil-avatar" src='<?php echo $user->getAvatar(); ?>' alt='avatar' width='100' height='100'/>
        </div>
        <div class="col-md-10 col-xs-12">
            <span class="full-name">
                 <?php echo $user->getFullName(); ?>
            </span>
            <span class="birthday">
                <i class="fa fa-birthday-cake" aria-hidden="true"></i>
                <?php echo $user->getDateDeNaissance()->format('d-m-Y'); ?>
            </span>
            <blockquote class="flow-text status-in" id="profil-status">
                <?php echo null !== $user->getStatut() && '' !== $user->getStatut() ? $user->getStatut(
                ) : 'Pas encore de status !'; ?>
            </blockquote>
        </div>
    </div>
    <?php if ($context::getSessionAttribute('id') === $user->getId()) { ?>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#EditCollapse"
                        aria-expanded="false" aria-controls="EditCollapse" id="edit-profile-cog">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                    Editer
                </button>
            </div>
        </div>
        <div class="collapse" id="EditCollapse">
            <div class="row">
                <div class="col-md-12">
                    <div class="row form-group">
                        <label class="col-sm-2 control-label" title="Status" for="form_status_edit">
                            Statut
                        </label>
                        <div class="col-sm-10">
                            <input type="text" id="form_status_edit" name="form_status_edit" placeholder="Status"
                                   class="form-control"/>
                            <input type="hidden" id="img-avatar-name" value="">
                            <input type="hidden" id="id-edition-user"
                                   value="<?php echo $context->__get('user')->getId() ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form action="?action=uploadImg&avatar=true" class="dropzone" id="monDropzoneAvatar"></form>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <button type="submit" id="submit-edit" name="form_envoyer" class="btn btn-success btn-lg"
                            value="Valider">
                        <i class="glyphicon glyphicon-ok"></i>
                        <span>Valider</span>
                    </button>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="well">
    <form name="form" method="post" class="form-horizontal">
        <div class="form-group">
            <label title="publication" class="col-sm-2 control-label required" for="form_publication">
                Votre publication
            </label>
            <div class="col-sm-8">
                <textarea id="form_publication" name="form[publication]" required="required"
                          class="form-control"></textarea>
                <input type="hidden" id="img-name" value="">
                <input type="hidden" id="id-user" value="<?php echo $context->__get('user')->getId() ?>">
            </div>
            <div class="col-sm-2">
                <button type="button" id="publication-submit" name="form_envoyer" class="btn btn-success btn-lg"
                        value="Envoyer">
                    <i class="glyphicon glyphicon-ok"></i>
                    <span>Envoyer</span>
                </button>
            </div>
        </div>
    </form>
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#dropZoneCollapse"
            aria-expanded="false" aria-controls="dropZoneCollapse">
        Ajouter une image
    </button>
    <div class="collapse" id="dropZoneCollapse">
        <form action="?action=uploadImg" class="dropzone" id="monDropzone"></form>
    </div>
    <div id="receive-ajax-message"></div>

    <?php
    /** @var \ifacebook\model\Message\Message $data */
    foreach ($context->__get('data') as $data) {
        ?>
        <div class="well">
            <div class="row">
                <?php if ($data->getEmetteur()) { ?>
                <div class="col-md-2 hidden-xs">
                    <img src='<?php echo $data->getEmetteur()->getAvatar(); ?>' alt='avatar' width='100' height='100'/>
                </div>
                <div class="col-md-10 col-xs-12">
                <span class="full-name">
                    <a href="?action=profil&id=<?php echo $data->getEmetteur()->getId(); ?>"
                       title="vers profil de <?php echo $data->getEmetteur()->getFullName(); ?>">
                        <?php echo $data->getEmetteur()->getFullName(); ?>
                    </a>
                </span>
                    <span class="date"><?php echo $data->getPost()->getDate()->format('d-m-Y H:i:s'); ?> </span>
                    <?php
                    if (null !== $data->getDestinataire() && $data->getDestinataire()->getId() !== $data->getEmetteur(
                        )->getId()
                    ) {
                        ?>
                        <span class="destination">Sur le mur de
                        <a href="?action=profil&id=<?php echo $data->getDestinataire()->getId(); ?>"
                           title="vers profil de <?php echo $data->getDestinataire()->getFullName(); ?>">
                        <?php echo $data->getDestinataire()->getFullName(); ?>
                        </a>
                    </span>
                        <?php
                    }
                    if (null !== $data->getParent()) {
                        ?>
                        <span class="partage-de">A partagé(e) de
                        <a href="?action=profil&id=<?php echo $data->getParent()->getId(); ?>"
                           title="vers profil de <?php echo $data->getParent()->getFullName(); ?>">
                        <?php echo $data->getParent()->getFullName(); ?>
                        </a>
                    </span>
                        <?php
                    }
                    ?>
                    <p class="flow-text">
                        <?php echo $data->getPost()->getTexte();
                        ?>
                    </p>
                    <?php
                    if ($data->getPost()->hasImage()) { ?>
                        <img src='<?php echo $data->getPost()->getImage(); ?>' alt='image de post'
                             class="img-responsive max-img"/>
                    <?php } ?>
                    <span id="like-<?php echo $data->getId(); ?>" class="like">
                    <?php echo $data->getAime() ?>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                </span>
                    <span id="partage-<?php echo $data->getId(); ?>" class="partage">
                    <i class="fa fa-share" aria-hidden="true"></i>
                    Partager
                </span>
                </div>
            </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
