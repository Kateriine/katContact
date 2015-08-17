<?php
if(!defined('ABSPATH')) exit; //exit if accessed directly

class Flickr_Feed{
    // function __construct() {
    //     add_action('wp_head', array(&$this, 'map_scripts'));
    //     $this->map = map();
    // }
    static $numPics;
    public function __construct($numberofPics=2)  {  
        $this->numPics = $numberofPics;
    }
    public function init() {   
        return $this->flickr_f();
    }
        
    private function flickr_f(){ 

          require_once( 'phpflickr/phpFlickr.php' );

          global $post;
          global $WP_Views;
          $output='';
          $apiKey = apply_filters( 'wpml_translate_single_string', get_option("katFlickRApiKey"), 'KatContact Data', 'katFlickRApiKey' );
          $apiSecret = apply_filters( 'wpml_translate_single_string', get_option("katFlickRApiSecret"), 'KatContact Data', 'katFlickRApiSecret' );
          $userPage = apply_filters( 'wpml_translate_single_string', get_option("katFlickrPage"), 'KatContact Data', 'katFlickrPage' );
          $f = new phpFlickr($apiKey, $apiSecret);
          $userInfo = $f->urls_lookupUser($userPage);
          $userId = $userInfo['id'];
          $photoset = $f->people_getPhotos($userId, array('per_page' => $this->numPics));

          if(isset($photoset['photos']['photo'])) {
            $output='<div class="uk-grid uk-grid-collapse">';
              foreach($photoset['photos']['photo'] as $photo) {
                $photo_url = $f->buildPhotoURL($photo, 'large');
                //$photo_url2 = $f->buildPhotoURL($photo, 'large_square');
                $output .= '<div class="uk-width-1-'.$this->numPics.'">';
                $output .= '<a class="fancybox" href="'.$photo_url.'" data-fancybox-group="gallery" title="'.$photo['title'].'"><span class="flickrImg" style="background-image:url('.$photo_url.');"></span></a>';
                $output .= '</div>';

              }
            $output .= '</div>';

          }
          return $output; 
    }

}
?>