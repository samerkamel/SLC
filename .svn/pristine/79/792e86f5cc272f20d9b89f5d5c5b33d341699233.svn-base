var MapsGoogle = function () {
    var mapGeolocation = function () {

        var map = new GMaps({
            div: '#gmap_geo',
            lat: 30.108417,
            lng: 31.376971
        });

        GMaps.geolocate({
            success: function (position) {
                map.setCenter(position.coords.latitude, position.coords.longitude);
            },
            error: function (error) {
                alert('Geolocation failed: ' + error.message);
            },
            not_supported: function () {
                alert("Your browser does not support geolocation");
            },
            always: function () {
                //alert("Geolocation Done!");
            }
        });

        map.addMarker({
           lat: 30.108287,
            lng: 31.374589,
            title: 'Marker with InfoWindow',
            infoWindow: {
                content: '<span style="color:#000">Unit information here</span>'
            }
        });
        map.addMarker({
           lat: 30.110106,
            lng: 31.376821,
            title: 'Marker with InfoWindow',
            infoWindow: {
                content: '<span style="color:#000">Unit information here</span>'
            }
        });
        map.addMarker({
           lat: 30.109086,
            lng: 31.377272,
            title: 'Marker with InfoWindow',
            infoWindow: {
                content: '<span style="color:#000">Unit information here</span>'
            }
        });

    }


    return {
        //main function to initiate map samples
        init: function () {
 
            mapGeolocation();

  
        }

    };

}();