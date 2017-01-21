$(document).ready(function () {
  /**
   * Fonction pour avertir si on quitte un formulaire que l'on a commencer de remplir.
   * Restriction : le bouton submit du formulaire doit avoir l'id "submit".
   * Restriction : Le formulaire doit etre dans une balise avec un id 'main-content'.
   * Restriction : Le formulaire ne doit pas être dans une classe 'rechercher'.
   * Restriction : Le formulaire protégé doit avoir la classe 'form-safety'
   */
  var formSafe = true;
  $('#main-content form').not('.no-safety').find(':input').change(function () {
    formSafe = false;
  });
  $('.rechercher form :input').change(function () {
    formSafe = true;
  });

  /**
   * Méthode contenant le message de la popup.
   *
   * @returns {string}
   */
  function unloadPage () {
    if (!formSafe) {
      return 'Vous avez commencé une saisie, êtes vous sur de vouloir quitter ?';
    }
  }

  $('button[type="submit"]').click(function () {
    formSafe = true;
  });
  $('button[id="submitProxy"]').click(function () {
    formSafe = true;
  });
  window.onbeforeunload = unloadPage;

});


