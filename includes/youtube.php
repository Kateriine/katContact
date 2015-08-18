<?php
if(!defined('ABSPATH')) exit; //exit if accessed directly
set_include_path(plugin_dir_path(__FILE__).'/googleapi');

class Youtube{
    // function __construct() {
    //     add_action('wp_head', array(&$this, 'map_scripts'));
    //     $this->map = map();
    // }
    private $numVids;
    private $numCols;
    private $yFeed;
    private $dTitle;
    public function __construct($numberofVids=2, $numberofCols=2, $displayTitle=false, $youtubeFeed='')  {  
        $this->numVids = $numberofVids;  
        $this->numCols = $numberofCols;  
        $this->yFeed = $youtubeFeed;  
        $this->dTitle = $displayTitle;
    }
    public function init() {   
        return $this->youtube_f();
    }
        
    private function youtube_f(){ 

        $output='';
        
        if($this->yFeed != '') {
            $set = $this->yFeed;
            if(substr($set, -1) != '/'){
                $set .= '/';
            }
           $allvidsId = substr($set, -35, -1);
           if ($this->dTitle == true) {
            $pListData = 'https://www.googleapis.com/youtube/v3/playlists?part=snippet&id='.$allvidsId.'&key=AIzaSyBYOAgjpER9pnK7jTXC9xF4MhqQ9x_apS8';
            $json = @file_get_contents($pListData,0,null,null);
            $json_output = @json_decode($json);;
            $title = $json_output->items[0]->snippet->title;
            $output .= '<h3>'.$title.'</h3>';
           }
        }
        else {

            $user = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=WIPGlobalForum&key='.get_option('katGoogleApi');

            $json = @file_get_contents($user,0,null,null);
            $json_output = @json_decode($json);
               
            $allvidsId = $json_output->items[0]->contentDetails->relatedPlaylists->uploads;
        }

        $feed = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId='.$allvidsId.'&maxResults='.$this->numVids.'&key='.get_option('katGoogleApi');
        $json = @file_get_contents($feed,0,null,null);
        $json_output = @json_decode($json);
           // echo '<pre>';
           //  print_r($json_output);
           // echo '</pre>';

        $output .= '<div class="uk-grid" data-uk-grid-margin>';
        foreach ( $json_output->items as $item ){;
            $photo_url = $item->snippet->thumbnails->high->url;
            $photo_w = $item->snippet->thumbnails->high->width;
            $photo_h = $item->snippet->thumbnails->high->height;
            $photo_id = $item->snippet->resourceId->videoId;
            $photo_title= $item->snippet->title;
            $output .= '<div class="uk-width-small-1-' . $this->numCols . '">';

            $output .= '<a class="fancybox fancybox.iframe" title="'.$photo_title.'" href="http://www.youtube.com/embed/' . $photo_id . '?autoplay=1&amp;enablejsapi=1&amp;html5=1&amp;vq=hd720" data-fancybox-group="gallery"><img src="'.$photo_url .'" alt="'.htmlentities($photo_title).'" width="'.$photo_w.'" height="'.$photo_h.'"/></a>';
            $output .= '</div>';
        }

        $output .= '</div>';

        
        return $output; 
    }

}
?>