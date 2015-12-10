<?php 
if(!defined('ABSPATH')) exit; //exit if accessed directly

class SocialData extends WP_Widget {
    private $itw_defaults = array();
    private $fields = array(
    'title'          => 'Title', 
    'text'          => 'Text', 
    );

    private $company = '';

  function __construct() {
    $widget_ops = array('classname' => 'contact-data widget_image_text_widget', 'description' => __('Shows social data', 'site'));

    parent::__construct('SocialData', __('Show social links', 'site'), $widget_ops);
    $this->alt_option_name = 'SocialData';
    
    $this->company = apply_filters( 'wpml_translate_single_string', get_option("katCompany"), 'KatContact Data', 'katCompany' );
    $this->address = apply_filters( 'wpml_translate_single_string', get_option("katAddress"), 'KatContact Data', 'katAddress' );
    $this->zipTown = apply_filters( 'wpml_translate_single_string', get_option("katZipTown"), 'KatContact Data', 'katZipTown' );
    $this->country = apply_filters( 'wpml_translate_single_string', get_option("katCountry"), 'KatContact Data', 'katCountry' );
    $this->phone = apply_filters( 'wpml_translate_single_string', get_option("katPhone"), 'KatContact Data', 'katPhone' );
    $this->fax = apply_filters( 'wpml_translate_single_string', get_option("katFax"), 'KatContact Data', 'katFax' );
    $this->email = apply_filters( 'wpml_translate_single_string', get_option("katEmail"), 'KatContact Data', 'katEmail' );
    $this->fbPage = apply_filters( 'wpml_translate_single_string', get_option("katFbPage"), 'KatContact Data', 'katFbPage' );
    $this->twitterPage = apply_filters( 'wpml_translate_single_string', get_option("katTwitterPage"), 'KatContact Data', 'katTwitterPage' );
    $this->youtubePage = apply_filters( 'wpml_translate_single_string', get_option("katYoutubePage"), 'KatContact Data', 'katYoutubePage' );
    $this->gplusPage = apply_filters( 'wpml_translate_single_string', get_option("katGplusPage"), 'KatContact Data', 'katGplusPage' );
    $this->linkedinPage = apply_filters( 'wpml_translate_single_string', get_option("katLinkedinPage"), 'KatContact Data', 'katLinkedinPage' );
    $this->instagramPage = apply_filters( 'wpml_translate_single_string', get_option("katInstagramPage"), 'KatContact Data', 'katInstagramPage' );
    $this->flickrPage = apply_filters( 'wpml_translate_single_string', get_option("katFlickrPage"), 'KatContact Data', 'katFlickrPage' );
    $this->pinterestPage = apply_filters( 'wpml_translate_single_string', get_option("katPinterestPage"), 'KatContact Data', 'katPinterestPage' );
    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    global $_wp_additional_image_sizes;
    $cache = wp_cache_get('socialData', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);


    foreach($this->fields as $name => $label) {
      if (!isset($instance[$name])) { $instance[$name] = ''; }
    }
    
    echo $before_widget;
    echo '<div class="widget">';

    echo '<div class="widget-content contact-content">';
    if ($instance['title']) {
      echo $before_title, $instance['title'], $after_title;
    }
    if ($instance['text']) {
      echo '<p>'. $instance['text'] . '</p>';
    }
    
    echo '<div class="uk-share">';
    echo '<ul>';
    if($this->fbPage !== '')
      echo '<li><a href="'.$this->fbPage.'"><i class="uk-icon uk-icon-facebook"></i></a></li>';
    if($this->twitterPage !== '')
      echo '<li><a href="'.$this->twitterPage.'"><i class="uk-icon uk-icon-twitter"></i></a></li>';
    if($this->youtubePage !== '')
      echo '<li><a href="'.$this->youtubePage.'"><i class="uk-icon uk-icon-youtube-play"></i></a></li>';
    if($this->gplusPage !== '')
      echo '<li><a href="'.$this->gplusPage.'"><i class="uk-icon uk-icon-google-plus"></i></a></li>';
    if($this->linkedinPage !== '')
      echo '<li><a href="'.$this->linkedinPage.'"><i class="uk-icon uk-icon-linkedin"></i></a></li>';
    if($this->instagramPage !== '')
      echo '<li><a href="'.$this->instagramPage.'"><i class="uk-icon uk-icon-instagram"></i></a></li>';
    if($this->flickrPage !== '')
      echo '<li><a href="'.$this->flickrPage.'"><i class="uk-icon uk-icon-flickr"></i></a></li>';
    if($this->pinterestPage !== '')
      echo '<li><a href="'.$this->pinterestPage.'"><i class="uk-icon uk-icon-pinterest"></i></a></li>';
    echo '</ul>';
       
    echo '</div>';
    echo '</div><!--/contact-content-->';
    echo '</div><!--/widget-->';
       
    
    echo $after_widget;  

    //$this->getUserLike();

    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('socialData', $cache, 'widget');
  }

  function hide_email($email) { 
    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);

    for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];

    $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
    $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
    $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; 
    $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';

    return '<span id="'.$id.'"></span>'.$script;

  }



  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['socialData'])) {
      delete_option('socialData');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('socialData', 'widget');
  }

  function form($instance) {
    foreach($this->fields as $name => $label) {
      ${$name} = isset($instance[$name]) ? esc_attr($instance[$name]) : '';
    ?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id($name)); ?>"><?php _e("{$label}:", 'site'); ?></label>
      
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id($name)); ?>" name="<?php echo esc_attr($this->get_field_name($name)); ?>" type="text" value="<?php echo ${$name}; ?>">
    </p>
    <?php
    }
  }
}
class ContactData extends WP_Widget {
    private $itw_defaults = array();
    private $fields = array(
    );

    private $company = '';

  function __construct() {
    $widget_ops = array('classname' => 'contact-data widget_image_text_widget', 'description' => __('Shows contact data', 'site'));

    parent::__construct('ContactData', __('Show contact data', 'site'), $widget_ops);
    $this->alt_option_name = 'ContactData';
    
    $this->company = apply_filters( 'wpml_translate_single_string', get_option("katCompany"), 'KatContact Data', 'katCompany' );
    $this->address = apply_filters( 'wpml_translate_single_string', get_option("katAddress"), 'KatContact Data', 'katAddress' );
    $this->zipTown = apply_filters( 'wpml_translate_single_string', get_option("katZipTown"), 'KatContact Data', 'katZipTown' );
    $this->country = apply_filters( 'wpml_translate_single_string', get_option("katCountry"), 'KatContact Data', 'katCountry' );
    $this->phone = apply_filters( 'wpml_translate_single_string', get_option("katPhone"), 'KatContact Data', 'katPhone' );
    $this->fax = apply_filters( 'wpml_translate_single_string', get_option("katFax"), 'KatContact Data', 'katFax' );
    $this->email = apply_filters( 'wpml_translate_single_string', get_option("katEmail"), 'KatContact Data', 'katEmail' );
    $this->fbPage = apply_filters( 'wpml_translate_single_string', get_option("katFbPage"), 'KatContact Data', 'katFbPage' );
    $this->twitterPage = apply_filters( 'wpml_translate_single_string', get_option("katTwitterPage"), 'KatContact Data', 'katTwitterPage' );
    $this->youtubePage = apply_filters( 'wpml_translate_single_string', get_option("katYoutubePage"), 'KatContact Data', 'katYoutubePage' );
    $this->gplusPage = apply_filters( 'wpml_translate_single_string', get_option("katGplusPage"), 'KatContact Data', 'katGplusPage' );
    $this->linkedinPage = apply_filters( 'wpml_translate_single_string', get_option("katLinkedinPage"), 'KatContact Data', 'katLinkedinPage' );
    $this->instagramPage = apply_filters( 'wpml_translate_single_string', get_option("katInstagramPage"), 'KatContact Data', 'katInstagramPage' );
    $this->pinterestPage = apply_filters( 'wpml_translate_single_string', get_option("katPinterestPage"), 'KatContact Data', 'katPinterestPage' );

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    global $_wp_additional_image_sizes;
    $cache = wp_cache_get('contactData', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);


    foreach($this->fields as $name => $label) {
      if (!isset($instance[$name])) { $instance[$name] = ''; }
    }
    
    echo $before_widget;
    echo '<div class="widget">';

    echo '<div class="widget-content contact-content">';
    //echo '<a href="' . site_url() . '"><img src="'. get_stylesheet_directory_uri() . '/images/logo-footer.svg" alt="" /></a>';
    
    echo '<p class="no-m-t">© <a href="' . site_url() . '">' . $this->company . '</a> &ndash; ' . __('All rights reserved', 'plugin') . '<br />'; 
    echo $this->address  . '<br />'; 
    echo $this->zipTown  . '</p>'; 
    echo '<p>' . __('Phone', 'plugin') . ': ' . $this->phone . '<br />'; 

    if($this->fax !== '')
      echo 'Fax: ' . $this->fax . '<br />'; 

    echo 'Mail: ' . hide_email($this->email);
       
    echo '</div><!--/contact-content-->';
    echo '</div><!--/widget-->';
    
    echo $after_widget;  

    //$this->getUserLike();

    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('contactData', $cache, 'widget');
  }

  function hide_email($email) { 
    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);

    for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];

    $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
    $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
    $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")"; 
    $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';

    return '<span id="'.$id.'"></span>'.$script;

  }



  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['contactData'])) {
      delete_option('contactData');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('contactData', 'widget');
  }

  function form($instance) {
    foreach($this->fields as $name => $label) {
      ${$name} = isset($instance[$name]) ? esc_attr($instance[$name]) : '';
    ?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id($name)); ?>"><?php _e("{$label}:", 'site'); ?></label>
      
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id($name)); ?>" name="<?php echo esc_attr($this->get_field_name($name)); ?>" type="text" value="<?php echo ${$name}; ?>">
    </p>
    <?php
    }
  }
}
class FacebookLike extends WP_Widget {
    private $itw_defaults = array();
    private $fbUrl = '';
    private $fields = array(
    'title'          => 'Title', 
    );

  function __construct() {
    $widget_ops = array('classname' => 'facebook-like widget_image_text_widget', 'description' => __('Show number of FB likes block', 'site'));

    parent::__construct('facebookLike', __('Show number of FB Likes', 'site'), $widget_ops);
    $this->alt_option_name = 'facebookLike';
    $this->fbUrl = apply_filters( 'wpml_translate_single_string', get_option("katFbPage"), 'KatContact Data', 'katFbPage' );
    $this->twitterUrl = apply_filters( 'wpml_translate_single_string', get_option("katTwitterPage"), 'KatContact Data', 'katTwitterPage' );
    $this->fbAppId = apply_filters( 'wpml_translate_single_string', get_option("kat_facebook_app_id"), 'KatContact Data', 'kat_facebook_app_id' );
    $this->fbAppSecret = apply_filters( 'wpml_translate_single_string', get_option("kat_facebook_app_secret"), 'KatContact Data', 'kat_facebook_app_secret' );

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
        global $_wp_additional_image_sizes;
        $w = $_wp_additional_image_sizes['masonry image']['width'];
        $h = $_wp_additional_image_sizes['masonry image']['height'];
    $cache = wp_cache_get('facebookLike', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);


    foreach($this->fields as $name => $label) {
      if (!isset($instance[$name])) { $instance[$name] = ''; }
    }
    


    $title = apply_filters('widget_title', empty($instance['title']) ? __('Join us', 'site') : $instance['title'], $instance, $this->id_base);
   
    
    
    echo $before_widget;
    echo '<div class="widget-content facebook-content">';
   if ($title) {
      echo $before_title, $instance['title'], $after_title;
    }
   echo '<div class="widget-link">';


    echo $this->getfbFollowers();
    
    echo '<div class="fb-button-place">
            <div class="fb-like" data-href="'. $this->fbUrl .'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false">
            </div>            
          </div>'; 

    echo '<div class="twitter-button-place">
            <a href="'. $this->twitterUrl .'" class="twitter-follow-button" data-show-count="true" data-show-screen-name="false">Follow</a></div>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>         
          </div>';  
       
    echo '</div><!--/facebook-content-->';
    
    echo $after_widget;  


    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('facebookLike', $cache, 'widget');
  }

   function getfbFollowers()
    {

        $fbId = substr($this->fbUrl, 25);
        $fbappid = $this->fbAppId;
        $fbappsecret = $this->fbAppSecret;

        $finfo = @json_decode(file_get_contents('https://graph.facebook.com/'.$fbId.'?access_token='.$fbappid.'|'.$fbappsecret));

        $fbcount = $finfo->likes / 1000;

            $html = '<a class="fb-likes" href="'.$this->fbUrl.'">
                                <span class="chicon">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><path d="M12 7.247V13H8v7h4v20h10V20h6s.7-3.47 1-7h-7V8.125C22 7.437 23.125 7 24.178 7H29V0h-6.736c-10.25 0-10.008 6.24-10.008 7.247H12z" fill="currentColor"/></svg>
                                  </span>'.
                               $fbcount
                            .'K</a>';

        return $html;
    }


  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['facebookLike'])) {
      delete_option('facebookLike');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('facebookLike', 'widget');
  }

  function form($instance) {
   
    foreach($this->fields as $name => $label) {
      ${$name} = isset($instance[$name]) ? esc_attr($instance[$name]) : '';
    ?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id($name)); ?>"><?php _e("{$label}:", 'site'); ?></label>
      
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id($name)); ?>" name="<?php echo esc_attr($this->get_field_name($name)); ?>" type="text" value="<?php echo ${$name}; ?>">
    </p>
    <?php
    }
    
  }
}

class facebookJoin extends WP_Widget {
   
  private $fields = array(
    'title'          => 'Title', 
  );
  function __construct() {
    $widget_ops = array('classname' => 'facebook-join', 'description' => __('Show "join us on FB" block', 'site'));

    parent::__construct('facebookJoin', __('Show "join us on FB" block', 'site'), $widget_ops);
    $this->alt_option_name = 'facebookJoin';
     $this->fbPage = apply_filters( 'wpml_translate_single_string', get_option("katFbPage"), 'KatContact Data', 'katFbPage' );
    $this->fbAppId = apply_filters( 'wpml_translate_single_string', get_option("kat_facebook_app_id"), 'KatContact Data', 'kat_facebook_app_id' );
    $this->fbAppSecret = apply_filters( 'wpml_translate_single_string', get_option("kat_facebook_app_secret"), 'KatContact Data', 'kat_facebook_app_secret' );

    add_action('save_post', array(&$this, 'flush_widget_cache'));
    add_action('deleted_post', array(&$this, 'flush_widget_cache'));
    add_action('switch_theme', array(&$this, 'flush_widget_cache'));
  }

  function widget($args, $instance) {
    $cache = wp_cache_get('facebookJoin', 'widget');

    if (!is_array($cache)) {
      $cache = array();
    }

    if (!isset($args['widget_id'])) {
      $args['widget_id'] = null;
    }

    if (isset($cache[$args['widget_id']])) {
      echo $cache[$args['widget_id']];
      return;
    }

    ob_start();
    extract($args, EXTR_SKIP);
    $title = apply_filters('widget_title', empty($instance['title']) ? __('Join us', 'site') : $instance['title'], $instance, $this->id_base);
   
    echo $before_widget;
    echo '<div class="widget-bg">';

    echo '</div>';
    echo '<div class="widget-content facebook-content">';
   echo '<div class="widget-link">';
   if ($title) {
      echo $before_title, $instance['title'], $after_title;
    }


    
    echo '<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.3&appId='. $this->fbAppId .'";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>
    <div class="fb-like-box" data-href="'.$this->fbPage.'" data-colorscheme="dark" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>';  
       
    echo '</div></div><!--/facebook-content-->';
    
    echo $after_widget;    

    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('facebookJoin', $cache, 'widget');
  }

  function update($new_instance, $old_instance) {
    $instance = array_map('strip_tags', $new_instance);

    $this->flush_widget_cache();

    $alloptions = wp_cache_get('alloptions', 'options');

    if (isset($alloptions['facebookJoin'])) {
      delete_option('facebookJoin');
    }

    return $instance;
  }

  function flush_widget_cache() {
    wp_cache_delete('facebookJoin', 'widget');
  }

  function form($instance) {
    foreach($this->fields as $name => $label) {
      ${$name} = isset($instance[$name]) ? esc_attr($instance[$name]) : '';
    ?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id($name)); ?>"><?php _e("{$label}:", 'site'); ?></label>
      
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id($name)); ?>" name="<?php echo esc_attr($this->get_field_name($name)); ?>" type="text" value="<?php echo ${$name}; ?>">
    </p>
    <?php
    }
    
  }
}