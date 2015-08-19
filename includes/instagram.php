<?php
if(!defined('ABSPATH')) exit; //exit if accessed directly

class Instagram_Feed{
    // function __construct() {
    //     add_action('wp_head', array(&$this, 'map_scripts'));
    //     $this->map = map();
    // }
    private $numPics;
    private $numCols;
    private $fFeed;
    public function __construct($numberofPics=2, $numberofCols=2)  {  
        $this->numPics = $numberofPics;  
        $this->numCols = $numberofCols; 
    }
    public function init() {   
        return $this->instagram_f();
    }
        
    private function instagram_f(){ 

        $output='';
        $access_token = get_option("katInstagramToken");
        $iPage = apply_filters( 'wpml_translate_single_string', get_option("katInstagramPage"), 'KatContact Data', 'katInstagramPage' );

        if(substr($iPage, -1) == '/'){
            $iPage = substr($iPage, count($iPage)-1, -1);
        }
        $iPageArr = explode("/", $iPage);
        $user = $iPageArr[3];

        $userData = 'https://api.instagram.com/v1/users/search?q='.$user.'&access_token='.$access_token;
        $json = @file_get_contents($userData,0,null,null);
        $json_output = json_decode($json);
        $userId = $json_output->data[0]->id;
        
        $url = 'https://api.instagram.com/v1/users/'.$userId.'/media/recent/?access_token='.$access_token;
        $json = @file_get_contents($url,0,null,null);
        $json_output = json_decode($json);
        $items = $json_output->data;
        $i = 1;
        if(isset($items)) {
            // echo '<pre>';
            // print_r($items);
            // echo '</pre>';

            $output='<div class="uk-grid uk-grid-collapse">';
            foreach ( $items as $item ){
                if (isset($item->videos)){    
                    $output .= '<div class="uk-width-1-'.$this->numCols.'">';   
                        $output .= '<a class="fancybox fancybox.iframe" href="'.$item->videos->standard_resolution->url.'" data-fancybox-group="gallery" title="'.htmlentities($item->caption->text).'"><img src="'.$item->images->standard_resolution->url.'" alt="'.htmlentities($item->caption->text).'" width="640" height="640" /></a>'; 
                    $output .= '</div>';       
                }
                else {
                    $output .= '<div class="uk-width-1-'.$this->numCols.'">';   
                    $output .= '<a class="fancybox" href="'.$item->images->standard_resolution->url.'" data-fancybox-group="gallery" title="'.htmlentities($item->caption->text).'"><img src="'.$item->images->standard_resolution->url.'" alt="'.htmlentities($item->caption->text).'" width="640" height="640" /></a>'; 
                    $output .= '</div>';
                } 

                if($i == $this->numPics)
                    break; 
                else
                    $i++;
            }
            $output .= '</div>';
        }
        return $output; 
    }

}
?>