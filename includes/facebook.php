<?php
if(!defined('ABSPATH')) exit; //exit if accessed directly

class KatFacebook{
    // function __construct() {
    //     add_action('wp_head', array(&$this, 'map_scripts'));
    //     $this->map = map();
    // }
    private $numStat;
    private $numCols;
    private $appId;
    private $appSecret;

    private $authToken;
    public function __construct()  {  
        $this->appId = get_option("kat_facebook_app_id");
        $this->appSecret = get_option("kat_facebook_app_secret");
    }
    public function get_fbFeed($numberofStatuses=2, $numberofCols=2) {  
        $this->numStat = $numberofStatuses;  
        $this->numCols = $numberofCols;   
        return $this->facebook_f();
    }


    public function get_fbShare($post_id){
      if($this->appId == '' || $this->appSecret == '') {
        return 'No app ID or secret';
      }
      $access_token = $this->appId . '|' . $this->appSecret;
      $json = @file_get_contents('https://graph.facebook.com/v2.7/?id=' . urlencode( get_permalink( $post_id ) ) . '&access_token=' . $access_token);
      $counts = @json_decode($json, true);

      if($counts && isset($counts["share"])){
        $shareCount = $counts["share"]["share_count"];
      }
      else {
        $shareCount = 0;
      }
      return $shareCount;
    }

        
    private function facebook_f(){ 

        $output='';
        $fbPage = apply_filters( 'wpml_translate_single_string', get_option("katFbPage"), 'KatContact Data', 'katFbPage' );

        if(substr($fbPage, -1) != '/'){
            $fbPage .= '/';
        }
        $fbPageArr = explode("/", $fbPage);
        $pageID = $fbPageArr[3];
        if(!isset($this->authToken)){
            $this->authToken = $this->fetchUrl('https://graph.facebook.com/oauth/access_token?type=client_cred&client_id='.$this->appId.'&client_secret='.$this->appSecret);
        }

        $page_data = $this->fetchUrl("https://graph.facebook.com/".$pageID ."/posts?limit=".$this->numStat.'&'.$this->authToken, true);
        //echo "https://graph.facebook.com/".$pageID ."/posts?limit=".$this->numStat.'&'.$this->authToken;
        if(isset($page_data->data)) {
            $output .='<div class="uk-grid" data-uk-grid-margin data-uk-grid-match>';
            foreach($page_data->data as $data) {
                $pic='';
                $picArr = explode('&url=', $data->picture);
                $pic = $picArr[1];
                $pic = str_replace(array('%3A', '%2F', '%3F', '%3D', '%2A', '&cfs=1', '&sx=0&sy=0&sw=533&sh=533'), array(':', '/', '?', '=', '*', '', ''), $pic);

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
                $idArr = explode('_', $data->id);
                $id = $idArr[1];
                $output .= '<div class="uk-width-1-'.$this->numCols.'">';
                if($pic != '') {
                    $output .= '<div class="uk-text-center">';
                    $output .= '<a href="https://www.facebook.com/'.$pageID.'/posts/'.$id.'">';
                    $output .= '<img src="'.$pic .'" alt="'.htmlentities($data->name) .'" />';
                    $output .= '</a>';
                    $output .= '</div>';

                }
                $m = $this->makeClickableLinks($data->message);
                $output .= '<p>'.$m.'</p>';
                $output .= '</div>';
            }
            $output .= '</div>';
            
        }
        return $output; 
    }

    private function fetchUrl($url,$decode=false){      
        //caching
        $ch = new CachedCurl();
        $retData = $ch->load_url($url);
        
        if($decode){
            $retData = json_decode($retData);
        }
        
        return $retData;
    }
    private function makeClickableLinks($s) {
      return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
    }

}


class CachedCurl {
        var $cache_time = 900; // 900 seconds [15 minutes]
        
    public function __construct($cache_time = 900) {                   
        $this->cache_time = $cache_time;
    }
                
    public function load_url($url, $post_fields = false, $headers = false) {
        $cache_key = strlen($url) . md5($url);
        
        // check for a cached result
        $result = get_transient($cache_key);
        
        if ($result === false) {                
            $result = wp_remote_get($url);
            
            if(is_wp_error($result)){
                $result = $result->get_error_message();
            } else {
                $result = isset($result['body']) ? $result['body'] : '';
            
                if(strlen($result)>2){
                    // store to cache
                    set_transient($cache_key, $result, $this->cache_time);
                }
            }
            
            return $result;
        } else {
            return $result;
        }
    }
}
?>