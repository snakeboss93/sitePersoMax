/* exported ArobanAdresseVille */
var ArobanAdresseVille = (function () {
  'use strict';
  var module = {};
  var idPaysFrance;

  /**
   * @param value
   */
  module.setIdPaysFrance = function (value) {
    idPaysFrance = value.toString();
  };

  /* eslint max-statements: ["error", 10, { "ignoreTopLevelFunctions": true }] */
  /* Un Module pattern serait mieux, mais ca marche et too late pour rendre cela propre */
  /**
   * Initialise le widget adresse pays ville avec les champs caché et ceux affichés.
   */
  module.initialisationAPV = function () {
    $('.villeLibre').each(function () {
      $(this).closest('.form-group').hide();
    });
    $('.cpLibre').each(function () {
      $(this).closest('.form-group').hide();
    });
    $('.suffixeCedex').each(function () {
      $(this).closest('.form-group').hide();
    });
    $('.AdressePaysVille').each(function () {
      $(this).val([idPaysFrance]).trigger('change');
    });
    if ($('.cedex').is(':checked')) {
      $('.cpLibre').each(function () {
        $(this).closest('.form-group').show();
      });
      $('.suffixeCedex').each(function () {
        $(this).closest('.form-group').show();
      });
    }
  };

  /**
   * Des qu'un changement opère sur les champs alors cette méthode capte pour pouvoir ajuster au besoins les affichage / masquage
   */
  module.changement = function () {
    $("[id$='adresse_ville']").attr('required', '');
    $("label[for$='adresse_ville']").addClass('required');
    $('.AdressePaysVille').change(function () {
      var wrapper = $(this).closest('div.address_widget');
      if ($(wrapper).find('.AdressePaysVille').first().val() !== idPaysFrance) {
        module.showFieldForPays75(wrapper);
        module.obligation(wrapper);
      } else {
        module.showFieldForOthePays(wrapper);
        module.obligation(wrapper);
      }
    });
    $('.cedex').change(function () {
      var wrapper = $(this).closest('div.address_widget');
      if ($(wrapper).find('.cedex').first().is(':checked')) {
        module.showFieldForCedex(wrapper);
        module.obligation(wrapper);
      } else {
        module.hideFieldForCedex(wrapper);
        module.obligation(wrapper);
      }
    });
  };

  /**
   * Recois le wrapper pour ajuster le cedex et les champs qui en découlent pour ajuster le fait de voir si ils sont
   * obligatoire ou non.
   *
   * @param wrapper
   */
  module.obligation = function (wrapper) {
    if ($(wrapper).find("[id$='adresse_cedex']").is(':checked')) {
      module.changeRequiredForAdresseCedex(wrapper);
    } else {
      if ($(wrapper).find("[id$='adresse_pays']").val() === idPaysFrance) {
        module.changeRequiredForPays75(wrapper);
      } else {
        module.changeRequiredForOtherPays(wrapper);
      }
    }
  };

  /**
   *
   * @param wrapper
   */
  module.changeRequiredForAdresseCedex = function (wrapper) {
    $(wrapper).find("[id$='adresse_suffixeCedex']").attr('required', '');
    $(wrapper).find("[id$='adresse_cpLibre']").attr('required', '');
    $(wrapper).find("[id$='adresse_ville']").attr('required', '');
    $(wrapper).find("label[for$='adresse_suffixeCedex']").addClass('required');
    $(wrapper).find("label[for$='adresse_cpLibre']").addClass('required');
    $(wrapper).find("label[for$='adresse_ville']").addClass('required');

    $(wrapper).find("[id$='adresse_villeLibre']").removeAttr('required');
    $(wrapper).find("label[for$='adresse_villeLibre']").removeClass('required');
  };

  /**
   *
   * @param wrapper
   */
  module.changeRequiredForPays75 = function (wrapper) {
    $(wrapper).find("[id$='adresse_ville']").attr('required', '');
    $(wrapper).find("label[for$='adresse_ville']").addClass('required');

    $(wrapper).find("[id$='adresse_villeLibre']").removeAttr('required');
    $(wrapper).find("[id$='adresse_cpLibre']").removeAttr('required');
    $(wrapper).find("[id$='adresse_suffixeCedex']").removeAttr('required');
    $(wrapper).find("label[for$='adresse_villeLibre']").removeClass('required');
    $(wrapper).find("label[for$='adresse_cpLibre']").removeClass('required');
    $(wrapper).find("label[for$='adresse_suffixeCedex']").removeClass('required');
  };

  /**
   *
   * @param wrapper
   */
  module.changeRequiredForOtherPays = function (wrapper) {
    $(wrapper).find("[id$='adresse_villeLibre']").attr('required', '');
    $(wrapper).find("[id$='adresse_cpLibre']").attr('required', '');
    $(wrapper).find("label[for$='adresse_villeLibre']").addClass('required');
    $(wrapper).find("label[for$='adresse_cpLibre']").addClass('required');

    $(wrapper).find("[id$='adresse_ville']").removeAttr('required');
    $(wrapper).find("[id$='adresse_suffixeCedex']").removeAttr('required');
    $(wrapper).find("label[for$='adresse_ville']").removeClass('required');
    $(wrapper).find("label[for$='adresse_suffixeCedex']").removeClass('required');
  };

  /**
   * Pour masquer le select2 associé au widget, il faut lui ajouter l'attribut data-associated-select2.
   * L'attribut data-associated-select2 doit avoir pour valeur celle de l'id du select2.
   *
   * @param wrapper
   */
  module.showFieldForPays75 = function (wrapper) {
    var associatedSelect2 = $(wrapper).closest('.address_widget').find('div').data('associated-select2');
    $('#' + associatedSelect2).first().closest('.form-group').hide();
    $(wrapper).find('.ville').first().closest('.form-group').hide();
    $(wrapper).find('.cpLibre').first().closest('.form-group').show();
    $(wrapper).find('.villeLibre').first().closest('.form-group').show();
    $(wrapper).find('.cedex').first().closest('.form-group').hide();
    $(wrapper).find('.suffixeCedex').first().closest('.form-group').hide();
  };

  /**
   * Pour afficher le select2 associé au widget, il faut lui ajouter l'attribut data-associated-select2.
   * L'attribut data-associated-select2 doit avoir pour valeur celle de l'id du select2.
   *
   * @param wrapper
   */
  module.showFieldForOthePays = function (wrapper) {
    var associatedSelect2 = $(wrapper).closest('.address_widget').find('div').data('associated-select2');
    $('#' + associatedSelect2).first().closest('.form-group').show();
    $(wrapper).find('.ville').first().closest('.form-group').show();
    $(wrapper).find('.cpLibre').first().closest('.form-group').hide();
    $(wrapper).find('.villeLibre').first().closest('.form-group').hide();
    $(wrapper).find('.cedex').first().closest('.form-group').show();
  };

  /**
   *
   * @param wrapper
   */
  module.showFieldForCedex = function (wrapper) {
    $(wrapper).find('.cpLibre').first().closest('.form-group').show();
    $(wrapper).find('.suffixeCedex').first().closest('.form-group').show();
  };

  /**
   *
   * @param wrapper
   */
  module.hideFieldForCedex = function (wrapper) {
    $(wrapper).find('.cpLibre').first().closest('.form-group').hide();
    $(wrapper).find('.suffixeCedex').first().closest('.form-group').hide();
  };

  return module;
})();

