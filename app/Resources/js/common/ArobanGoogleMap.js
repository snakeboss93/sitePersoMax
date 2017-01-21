$(document).ready(function () {
  /* eslint max-statements: ["error", 15] */
  /* Cela aurait était bien de le faire correct dès le départ, la c'est trop chaud de changer. */
  
  /** Pour la google map **/
  var gmapLoader = {};
  gmapLoader.alreadyInitialized = false;

  gmapLoader.init = function () {
    if (gmapLoader.alreadyInitialized) {
      return;
    }
    gmapLoader.alreadyInitialized = true;

    // 1. récuypère les wrapper du type de widget gmap
    $('div[data-widget-type]').each(function () {
      var $divMap = $(this);

      GoogleMapsLoader.KEY = 'AIzaSyCMNGPEl24C7zDLfKW2Hj3bvX6AluZpm8I';
      GoogleMapsLoader.LANGUAGE = 'fr';
      GoogleMapsLoader.load(function (google) {
        var map = new google.maps.Map(document.getElementById($divMap.attr('id')), {
          center: {lat: 43.940, lng: 4.806},
          scrollwheel: false,
          zoom: 8
        });

        // Hydrater les champs lat et lng
        var $latField = $('#' + $divMap.attr('id').replace('map', 'lat'));
        var $lngField = $('#' + $divMap.attr('id').replace('map', 'lng'));
        var marker = null;

        // Si champs renseigner, aller au coord et dessiner un marker
        if (($latField.val() !== '') && ($lngField.val() !== '')) {
          map.panTo({lat: Number($latField.val()), lng: Number($lngField.val())});
          if (marker === null) {
            marker = new google.maps.Marker({
              position: {lat: Number($latField.val()), lng: Number($lngField.val())},
              map: map
            });
          } else {
            marker.setMap(null);
            marker = new google.maps.Marker({
              position: {lat: Number($latField.val()), lng: Number($lngField.val())},
              map: map
            });
          }
        }

        // Avoir un marker la ou on click
        map.addListener('click', function (e) {
          map.panTo(e.latLng);
          $latField.val(e.latLng.lat());
          $lngField.val(e.latLng.lng());

          if (marker === null) {
            marker = new google.maps.Marker({
              position: e.latLng,
              map: map
            });
          } else {
            marker.setMap(null);
            marker = new google.maps.Marker({
              position: e.latLng,
              map: map
            });
          }
        });
      });
    });
  };

  gmapLoader.init();
});
