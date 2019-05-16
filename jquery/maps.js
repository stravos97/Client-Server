
class Map{

  constructor() {

  }

  initialize() {


      var map;



      // get map marker data from the view object locations field and convert it
      // from a php array to json arrary so it can be read in js below
      var locations = <?php echo json_encode($view->locations); ?>;
      console.log(locations);

      // make a new map object and attached it to the display element
      var map = new google.maps.Map(
          document.getElementById('map'),
          {
              zoom: 1, center: new google.maps.LatLng(37.0902, 95.7129),
              mapTypeId: google.maps.MapTypeId.ROADMAP
          }
      );

      /**
      * Search
      **/

      // Create the search box and link it to the UI element.
var input = document.getElementById('pac-input');
var searchBox = new google.maps.places.SearchBox(input);


map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);


// Bias the SearchBox results towards current map's viewport.
map.addListener('bounds_changed', function() {
searchBox.setBounds(map.getBounds());
});

var markers = [];
// Listen for the event fired when the user selects a prediction and retrieve
// more details for that place.
searchBox.addListener('places_changed', function() {
var places = searchBox.getPlaces();

if (places.length == 0) {
  return;
}

// Clear out the old markers.
markers.forEach(function(marker) {
  marker.setMap(null);
});
markers = [];

// For each place, get the icon, name and location.
var bounds = new google.maps.LatLngBounds();
places.forEach(function(place) {
  if (!place.geometry) {
    console.log("Returned place contains no geometry");
    return;
  }
  var icon = {
    url: place.icon,
    size: new google.maps.Size(71, 71),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(17, 34),
    scaledSize: new google.maps.Size(25, 25)
  };

  // Create a marker for each place.
  markers.push(new google.maps.Marker({
    map: map,
    icon: icon,
    title: place.name,
    position: place.geometry.location
  }));

  if (place.geometry.viewport) {
    // Only geocodes have viewport.
    bounds.union(place.geometry.viewport);
  } else {
    bounds.extend(place.geometry.location);
  }
});
map.fitBounds(bounds);
});

/**
* Search
**/


      var marker, i;

      var markerCluster = new MarkerClusterer(map, marker,{
        imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
        zoomOnClick: true
      });

      // make jsut one info window so that you only see one at a time when a marker is clicked
      var infowindow = new google.maps.InfoWindow();

      // go through the array of location data and create a marker and info window
      // for each location adding meta data to the info window

      for (i = 0; i < locations.length; i++)
         {
             // pull the marker data into named variables
             var name = locations[i][0];
             var lat = locations[i][1];
             var long = locations[i][2];
             var photo = locations[i][4];
             var id = locations[i][3];
             // create a latlng object for the marker
             latlngset = new google.maps.LatLng(lat, long);
             // add the marker
             var marker = new google.maps.Marker({
                  map: map, title: name , position: latlngset
             });


             //ADD the marker to the markerCluster
             markerCluster.addMarker(marker);
             // set formatted content for this marker
             var content = "<div><img src='img/" + photo + "' ><p>" + name + "</p><p><a href='info_content.php?id=" + id + " &name=" + name +"'>Click for more info</a></p><div>";
             // add a click event to this marker (and each one) and apply the content
             google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){
                  return function() {
                      infowindow.setContent(content);
                      infowindow.open(map,marker);
                  };
             })(marker,content,infowindow));

          }


     }

     function runMyTest() {
      self::initialize();
}

}
