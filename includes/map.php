<?php
if(!defined('ABSPATH')) exit; //exit if accessed directly

$map= new Kat_Map;
//$map::addMapScripts();
class Kat_Map{
    // function __construct() {
    //     add_action('wp_head', array(&$this, 'map_scripts'));
    //     $this->map = map();
    // }
    public function __construct()  {
        wp_register_script('gmaps', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'), '', false);
        wp_enqueue_script('gmaps');
        wp_register_script('infobox', plugins_url('js/infobox.js', __FILE__), array('jquery'), '', false);
        wp_enqueue_script('infobox');
                
    }
    static function init(  ) { 
        return self::map();
    }
        
    private function map(){ 

        $html = '<section id="map"></section>';
        $html .= '<script>
                jQuery(document).ready(function($) {

                var directionsText = "'. __("See on Google Maps", "site") .'",
                path = "'. get_template_directory_uri() . '/",
                map, 
                address,
                latitude, 
                longitude,
                styles,
                bounds, 
                anchor, 
                closestAgencyMarker, 
                searchResults,
                direction = [], 
                ext = ".svg";
                ';
        $html .= 'if (document.getElementsByClassName(".lt-ie9").length != 0){
                ext = ".png";
            }';
        $html .= '
        function initialize() {
                // Create a simple map.
               
                var geocoder = new google.maps.Geocoder();
                    address = "' . apply_filters( 'wpml_translate_single_string', get_option("katAddress"), 'KatContact Data', 'katAddress' ) . ', ' . apply_filters( 'wpml_translate_single_string', get_option("katZipTown"), 'KatContact Data', 'katZipTown' ) . '";

                geocoder.geocode( { "address": address}, function(results, status) {

                    if (status == google.maps.GeocoderStatus.OK) {
                        latitude = results[0].geometry.location.G;
                        longitude = results[0].geometry.location.K;
                        styles = [{
                            "featureType":"water",
                            "stylers":[{"color":"'.get_option('katWaterColor').'"},{"visibility":"on"}]
                        },
                        {
                            "featureType":"water",
                            "elementType":"labels.text.fill",
                            "stylers":[{"color":"'.get_option('katWaterLabelColor').'"}]
                        },
                        {
                            "featureType":"water",
                            "elementType":"labels.text.stroke",
                            "stylers":[{"visibility":"off"}]
                        },
                        {
                            "featureType":"landscape",
                            "elementType":"labels.text.fill",
                            "stylers":[{"color":"'.get_option('katLandscapeLabelColor').'"}]
                        },
                        {
                            "featureType":"landscape",
                            "elementType": "geometry.fill",
                            "stylers":[{"color":"'.get_option('katlandscapeColor').'"}]
                        },
                        {
                            "featureType":"road",
                            "stylers":[{"saturation":-100},{"lightness":45}]
                        },
                        {
                            "featureType":"road.highway",
                            "stylers":[{"visibility":"simplified"},{"color":"'.get_option('katHighwayColor').'"}]
                        },
                        {
                            "featureType":"road",
                            "elementType": "geometry.fill",
                            "stylers":[{"color":"'.get_option('katRoadColor').'"}]
                        },
                        {
                            "featureType":"road",
                            "elementType": "geometry.stroke",
                            "stylers":[{"color":"'.get_option('katRoadStrokeColor').'"}]
                        },
                        {
                            "featureType":"road.highway",
                            "elementType":"labels",
                            "stylers": [
                                { "visibility": "off" },
                            ]
                        },
                        {
                            "featureType":"road.arterial",
                            "elementType":"labels.icon",
                            "stylers":[{"visibility":"off"}]
                        },
                        {
                            "featureType":"administrative",
                            "elementType":"labels.text.fill",
                            "stylers":[{"color":"'.get_option('katAdministrativeLabelsColor').'"}]
                        },
                        {
                            "featureType":"transit",
                            "stylers":[{"visibility":"off"}]
                        },
                        {
                            "featureType":"poi",
                            "elementType": "geometry.fill",
                            "stylers":[{"color":"'.get_option('katPoiColor').'"}]
                        },
                        {
                            "featureType":"poi.park","stylers":[{"color":"'.get_option('katParksColor').'"}]
                        },
                        {
                            "featureType":"poi",
                            "elementType":"labels.text.fill",
                            "stylers":[{"color":"'.get_option('katPoiLabelColor').'"}]
                        },
                        {
                            "featureType":"poi",
                            "elementType":"labels.text.stroke",
                            "stylers":[{"color":"'.get_option('katWaterColor').'"}]
                        }
                        ];
                        addMap();
                    } 
                }); 
            }   

        ';
        $html .= '
            function addMap(){
                var mapOptions = {
                        center: {lat: latitude, lng: longitude},
                        zoom: 15,
                        scrollwheel: false,
                        styles: styles
                    },

                infoWindow = new google.maps.InfoWindow({
                    content: ""
                });
                map = new google.maps.Map(document.getElementById("map"),mapOptions);
                bounds = new google.maps.LatLngBounds();
                addMarkers();  
            }
        ';
        $html .= '
            function addMarkers(){            
                        markerIcon = "markerIcon.svg";
                       
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(latitude, longitude), 
                            map: map,
                            icon: path + "images/" + markerIcon,
                            visible: true
                        });
                        resAddress = address.replace(/ /gi, "+");
                        googleDir = "https://www.google.be/maps/search/" + resAddress + "/";

                        /* Create infobox */
                        var ibMarker = createInfoBox(marker, -100, false, false);  
                        addMarkersListeners(marker, ibMarker);                 
            } 

        ';
        $html .= '
            function addMarkersListeners(marker, ibMarker){        

                google.maps.event.addListener(marker, "click", function (e) {
                   
                    ibMarker.open(map, this);
                    map.panTo(marker.getPosition());

                    var infoBoxHalfHeight = $(".infoBox").height()/2;
                    
                });
            }
        ';
        $html .= '
            function createInfoBox(marker, marginLeft, opened, old){
                var myOptions = {
                     content: address 
                    ,disableAutoPan: false
                    ,maxWidth: 0
                    ,pixelOffset: new google.maps.Size(marginLeft, -5)
                    ,zIndex: null
                    ,pane:"mapPane"
                    ,alignBottom: true
                    ,closeBoxMargin: "10px 2px 2px 2px"
                    ,closeBoxURL: "<i class=\'chicon chicon-close pull-right\'>&times;</i>"
                    ,infoBoxClearance: new google.maps.Size(1, 1)
                    ,isHidden: false
                    ,pane: "floatPane"
                    ,enableEventPropagation: false
                };
                ib = new InfoBox(myOptions);
                return ib;
            }
            ';
        $html .= '
                google.maps.event.addDomListener(window, "load", initialize);
            });
        ';
        $html .= '
        </script>
        ';    

        return $html;   
    }

}
?>