<?php 
if(!defined('ABSPATH')) exit; //exit if accessed directly
class ContactData extends WP_Widget {
    private $itw_defaults = array();
    private $fields = array(
    );

    private $company = '';

  function __construct() {
    $widget_ops = array('classname' => 'contact-data widget_image_text_widget', 'description' => __('Shows contact data', 'site'));

    $this->WP_Widget('ContactData', __('Show contact data', 'site'), $widget_ops);
    $this->alt_option_name = 'ContactData';
    
    $this->company = get_option('katCompany');
    $this->address = get_option('katAddress');
    $this->zipTown = get_option('katZipTown');
    $this->country = get_option('katCountry');
    $this->phone = get_option('katPhone');
    $this->fax = get_option('katFax');
    $this->email = get_option('katEmail');
    $this->fbPage = get_option('katFbPage');
    $this->twitterPage = get_option('katTwitterPage');
    $this->youtubePage = get_option('katYoutubePage');
    $this->gplusPage = get_option('katGplusPage');
    $this->linkedinPage = get_option('katLinkedinPage');
    $this->instagramPage = get_option('katInstagramPage');
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

    echo '<div class="widget-content contact-content uk-text-center">';
    //echo '<a href="' . site_url() . '"><img src="'. get_stylesheet_directory_uri() . '/images/logo-footer.svg" alt="" /></a>';
    
    echo '<p class="no-m-t">© <a href="' . site_url() . '">' . $this->company . '</a> &ndash; ' . __('All rights reserved', 'plugin') . '<br />'; 
    echo $this->address  . '<br />'; 
    echo $this->zipTown  . '</p>'; 
    echo '<p>' . __('Phone', 'plugin') . ': ' . $this->phone . '<br />'; 

    if($this->fax !== '')
      echo 'Fax: ' . $this->phone . '<br />'; 

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
    private $fields = array(
      'button'          => 'Button text (optional)'
    );

    private $fbUrl = '';

  function __construct() {
    $widget_ops = array('classname' => 'facebook-like widget_image_text_widget', 'description' => __('Show number of FB likes block', 'site'));

    $this->WP_Widget('facebookLike', __('Show number of FB Likes', 'site'), $widget_ops);
    $this->alt_option_name = 'facebookLike';
    
    $this->fbUrl = get_option('katFbPage');

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

    $btn = empty($instance['button']) ? __('Like', 'site') : $instance['button'];

    foreach($this->fields as $name => $label) {
      if (!isset($instance[$name])) { $instance[$name] = ''; }
    }
    
    
    echo $before_widget;
    echo '<div class="widget-content facebook-content">';
   echo '<div class="widget-link">';


    echo $this->getfbFollowers();
    
    echo '<div class="fb-button-place">
            <div class="fb-like" data-href="'. $this->fbUrl .'" data-layout="button" data-action="like" data-show-faces="false" data-share="false">
            </div>
            <!--div class="fb-like" data-href="'. $this->fbUrl .'" data-layout="button" data-action="like" data-show-faces="false" data-share="false">
            </div>
            <div class="fb-like" data-href="'. $this->fbUrl .'" data-layout="button" data-action="like" data-show-faces="false" data-share="false">
            </div>
            <div class="fb-like" data-href='. $this->fbUrl .'" data-layout="button" data-action="like" data-show-faces="false" data-share="false">
            </div>
            <span class="uk-button uk-button-primary uk-button-round">'.$btn.'</span -->
            </div>';  
       
    echo '</div></div><!--/facebook-content-->';
    
    echo $after_widget;  


    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('facebookLike', $cache, 'widget');
  }

   function getfbFollowers()
    {

        $fbId = substr($this->fbUrl, 25);
        
        $finfo = @json_decode(file_get_contents('http://graph.facebook.com/'.$fbId));

        $fbcount = $finfo->likes / 1000;

            $html = '<a class="uk-h1" href="'.$this->fbUrl.'">
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

    $this->WP_Widget('facebookJoin', __('Show "join us on FB" block', 'site'), $widget_ops);
    $this->alt_option_name = 'facebookJoin';

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
      echo '<span class="uk-h3">' . $title . '</span>';
    }


    
    echo '<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.3&appId='.get_option('kat_facebook_app_id').'";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>
    <div class="fb-like-box" data-href="'.get_option('katFbPage').'" data-colorscheme="dark" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>';  
       
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