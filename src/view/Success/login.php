<h2>Connection</h2>

<form name="form" method="post" class="form-horizontal" action="?action=login">
    <div class="form-group">
        <label title="Login" class="col-sm-2 control-label required" for="form_login">
            Identifiant
        </label>
        <div class="col-sm-10">
            <input type="text" id="form_login" name="form[login]" required="required" placeholder="Login"
                   class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label title="mot de passe" class="col-sm-2 control-label required" for="form_pass">
            Mot de passe
        </label>
        <div class="col-sm-10">
            <input type="password" id="form_pass" name="form[pass]" required="required" placeholder="Mot de passe"
                   class="form-control"/>
        </div>
    </div>
    <div class="form-group text-center">
        <button type="submit" id="submit" name="form_envoyer" class="btn btn-success btn-lg" value="Valider">
            <i class="glyphicon glyphicon-ok"></i>
            <span>Valider</span>
        </button>
    </div>
</form>
