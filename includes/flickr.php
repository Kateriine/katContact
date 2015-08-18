<?php
if(!defined('ABSPATH')) exit; //exit if accessed directly

class Flickr_Feed{
    // function __construct() {
    //     add_action('wp_head', array(&$this, 'map_scripts'));
    //     $this->map = map();
    // }
    private $numPics;
    private $numCols;
    private $fFeed;
    private $dTitle;
    public function __construct($numberofPics=2, $numberofCols=2, $displayTitle=false, $flickrFeed='')  {  
        $this->numPics = $numberofPics;  
        $this->numCols = $numberofCols;  
        $this->fFeed = $flickrFeed;  
        $this->dTitle = $displayTitle;
    }
    public function init() {   
        return $this->flickr_f();
    }
        
    private function flickr_f(){ 

        require_once( plugin_dir_path(__FILE__).'phpflickr/phpFlickr.php' );

        $output='';
        $apiKey = get_option("katFlickRApiKey");
        $apiSecret = get_option("katFlickRApiSecret");
        if($this->fFeed != '') {
            $set = $this->fFeed;  
            if(substr($set, -1) != '/'){
                $set .= '/';
            }
            $feed = substr($set, -18, -1);

            $f = new phpFlickr($apiKey, $apiSecret);

            //$userInfo = $f->urls_lookupUser($feed);
            $photoset = $f->photosets_getPhotos($feed, 3, 'machine_tags,o_dims', $this->numPics, 1);
            if($this->dTitle) $output .= $photoset['photoset']['title'];
            if(isset($photoset['photoset']['photo'])) {
              $output .='<div class="uk-grid uk-grid-collapse">';
              foreach($photoset['photoset']['photo'] as $photo) {+

                $photo_url = $f->buildPhotoURL($photo, 'large');
                $photo_thumb = $f->buildPhotoURL($photo, 'medium');

                //$photo_url2 = $f->buildPhotoURL($photo, 'large_square');
                $output .= '<div class="uk-width-1-'.$this->numCols.'">';
                $output .= '<a class="fancybox" href="'.$photo_url.'" data-fancybox-group="gallery" title="'.htmlentities($photo['title']).'"><img src="'.$photo_thumb.'" alt="'.htmlentities($photo['title']).'" width="500" height="500"/></a>';
                $output .= '</div>';

              }
              $output .= '</div>';
            }
        }
        else {
            $feed = apply_filters( 'wpml_translate_single_string', get_option("katFlickrPage"), 'KatContact Data', 'katFlickrPage' );

            $f = new phpFlickr($apiKey, $apiSecret);
            $userInfo = $f->urls_lookupUser($feed);
            $userId = $userInfo['id'];
            $photoset = $f->people_getPhotos($userId, array('per_page' => $this->numPics));
            if(isset($photoset['photos']['photo'])) {
                $output='<div class="uk-grid uk-grid-collapse">';
                  foreach($photoset['photos']['photo'] as $photo) {
                    $photo_url = $f->buildPhotoURL($photo, 'large');
                    $photo_thumb = $f->buildPhotoURL($photo, 'medium');
                    //$photo_url2 = $f->buildPhotoURL($photo, 'large_square');
                    $output .= '<div class="uk-width-1-'.$this->numCols.'">';
                    $output .= '<a class="fancybox" href="'.$photo_url.'" data-fancybox-group="gallery" title="'.htmlentities($photo['title']).'"><img src="'.$photo_thumb.'" alt="'.htmlentities($photo['title']).'" width="500" height="500"/></a>';
                    $output .= '</div>';

                  }
                $output .= '</div>';

            }
        }
        return $output; 
    }

}
?>