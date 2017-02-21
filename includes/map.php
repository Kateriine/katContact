<?php
if(!defined('ABSPATH')) exit; //exit if accessed directly

$map= new Kat_Map;
//$map::addMapScripts();
class Kat_Map{
    // function __construct() {
    //     add_action('wp_head', array(&$this, 'map_scripts'));
    //     $this->map = map();
    // }
    static $maps_api_url_js         = null;
    static $maps_api_url_geocode    = null;
    static $address    = null;

    public function __construct()  {


        self::$maps_api_url_js      = 'https://maps.googleapis.com/maps/api/js';
        self::$maps_api_url_geocode = 'https://maps.googleapis.com/maps/api/geocode/json';
        self::$address = apply_filters( 'wpml_translate_single_string', get_option("katAddress"), 'KatContact Data', 'katAddress' ) . ', ' . apply_filters( 'wpml_translate_single_string', get_option("katZipTown"), 'KatContact Data', 'katZipTown' ) ;       

        //add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 20 );
                
    }
    public function init(  ) { 
        $this->register_assets();
        return $this->map();
    }
    private function register_assets() {
        $maps_api_js_url = self::$maps_api_url_js;
        wp_register_script('infobox', plugins_url('js/infobox.js', __FILE__), array('jquery'), '', false);

        wp_register_script(
            'google-maps',
            esc_url(
                add_query_arg(
                    array(
                        'key' => get_option('katGoogleApi')
                    ),
                    $maps_api_js_url
                )
            ),
            array(),
            '',
            true
        );
        wp_enqueue_script('google-maps');
        wp_enqueue_script('infobox');


    }
    private function get_coords(){

        $ln = get_locale();
        if(get_transient('site_coord' . $ln)) {
            $coords = get_transient('site_coord' . $ln);
        }
        else {

            $args = array( 'address' => urlencode( self::$address ) );
            $maps_api_url_geocode = self::$maps_api_url_geocode;
            $url        = add_query_arg( $args, $maps_api_url_geocode );
            $response   = wp_remote_get( $url, array( 'decompress' => false ) );
            if ( is_wp_error( $response ) ) {
                return __( 'wp_remote_get could not communicate with the Google Maps API.', 'toolset-maps' );
            }

            $data = wp_remote_retrieve_body( $response );
            if ( is_wp_error( $data ) ) {
                return sprintf(
                    __( 'wp_remote_retrieve_body could not get data from the the Gogle Maps API response. URL was %s', 'toolset-maps' ),
                    $url
                );
            }
            $html = '';
            if ( $response['response']['code'] == 200 ) {
                $data = json_decode( $data );
                if ( $data->status === 'OK' ) {
                    $coords = $data->results[0]->geometry->location;
                    set_transient('site_coord' . $ln, $coords, 86400);
                }
            }

            $coords = get_transient('site_coord' . $ln);            
        }
        return $coords;
    }
        
    private function map(){ 

        $coordinates = $this->get_coords();
        //TODO: enqueue this with wp_localize_script()
        $html = '<section id="map"></section>';
        $html .= '<script>
                jQuery(document).ready(function($) {

                var directionsText = "'. __("See on Google Maps", "site") .'",
                path = "'. get_template_directory_uri() . '/",
                map, 
                address = "'.self::$address.'",
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
            latitude = '.$coordinates->lat.';
            longitude = '.$coordinates->lng.';         

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