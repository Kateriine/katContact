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
    $this->itw_defaults = array(
            'image_id' => 0
    );
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
    echo '<a href="' . site_url() . '"><img src="'. get_stylesheet_directory_uri() . '/images/logo-footer.svg" alt="" /></a>';
    
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
    $image_id = (int)(isset($instance['image_id']) ? $instance['image_id'] : $this->itw_defaults['image_id']);
    if($image_id !== 0)
            $image = wp_get_attachment_image_src($image_id, 'thumbnail', FALSE);
        else
            $image[0] = '';
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
      'button'          => 'Button text (optional)', 
      'image_id'            => 'Picture'
    );

    private $fbUrl = '';

  function __construct() {
    $widget_ops = array('classname' => 'facebook-like widget_image_text_widget', 'description' => __('Show number of FB likes block', 'site'));

    $this->WP_Widget('facebookLike', __('Show number of FB Likes', 'site'), $widget_ops);
    $this->alt_option_name = 'facebookLike';
    $this->itw_defaults = array(
            'image_id' => 0
    );
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
    $image = wp_get_attachment_image_src($instance['image_id'], 'masonry image', FALSE);
    $image = apply_filters('itw_widget_image', $image, $instance);
    $timthumb_script = get_template_directory_uri() . '/external/timthumb.php?src=';
    $timthumb_params = '&amp;q=80&amp;w=' . $w . '&amp;h=' . $h . '&amp;f=2';
    
    echo $before_widget;
    echo '<div class="widget-bg" style="background-image:url('.$timthumb_script . $image[0] . $timthumb_params.')">';


    echo '</div>';
    echo '<div class="widget-content facebook-content">';
   echo '<div class="widget-link">';


    echo $this->getfbFollowers();
    
    echo '<div class="fb-button-place">
            <div class="fb-like" data-href="'. $this->fbUrl .'" data-layout="button" data-action="like" data-show-faces="false" data-share="false">
            </div>
            <div class="fb-like" data-href="'. $this->fbUrl .'" data-layout="button" data-action="like" data-show-faces="false" data-share="false">
            </div>
            <div class="fb-like" data-href="'. $this->fbUrl .'" data-layout="button" data-action="like" data-show-faces="false" data-share="false">
            </div>
            <div class="fb-like" data-href='. $this->fbUrl .'" data-layout="button" data-action="like" data-show-faces="false" data-share="false">
            </div>
            <span class="uk-button uk-button-primary uk-button-round">'.$btn.'</span>
            </div>';  
       
    echo '</div></div><!--/facebook-content-->';
    
    echo $after_widget;  


    $cache[$args['widget_id']] = ob_get_flush();
    wp_cache_set('facebookLike', $cache, 'widget');
  }

   function getfbFollowers()
    {

        $fbId = substr($this->fbUrl, 25);
        
        $finfo = json_decode(file_get_contents('http://graph.facebook.com/'.$fbId));

        $fbcount = $finfo->likes / 1000;

            $html = '<a class="uk-h1" href="'.$this->fbUrl.'">
                               <span class="uk-icon-circled">
                                  <svg class="chicon">
                                      <use xlink:href="' .get_stylesheet_directory_uri() . '/images/icons.svg#chicon-facebook" />
                                  </svg>
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
    $image_id = (int)(isset($instance['image_id']) ? $instance['image_id'] : $this->itw_defaults['image_id']);
    if($image_id !== 0)
            $image = wp_get_attachment_image_src($image_id, 'thumbnail', FALSE);
        else
            $image[0] = '';
    foreach($this->fields as $name => $label) {
      ${$name} = isset($instance[$name]) ? esc_attr($instance[$name]) : '';
    ?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id($name)); ?>"><?php _e("{$label}:", 'site'); ?></label>
      
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id($name)); ?>" name="<?php echo esc_attr($this->get_field_name($name)); ?>" type="text" value="<?php echo ${$name}; ?>">
    </p>
    <?php
    }
    echo '<div>
                <div class="itw-image-buttons">
                    <input class="itw_upload_image_id" type="hidden" name="'.$this->get_field_name('image_id').'" value="'.$image_id.'" />
                    <input class="itw_upload_image_button button button-secondary" type="button" value="'.__('Select image', 'image-text-widget').'" />
                    <input class="itw_turn_off_image_button button button-secondary" type="button" value="'.__('Remove image', 'image-text-widget').'" '.disabled(0, $image_id, FALSE).' />
                    <span class="itw-spinner"></span>
                </div>
                <div class="itw-image-preview">
                    '.($image[0] !== '' ? '<img src="'.$image[0].'" alt="" />' : '<img src="" alt="" style="display: none;" />').'
                </div>
            </div>'; 
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


    
    echo '<div class="fb-like-box" data-href="'.get_option('katFbPage').'" data-colorscheme="dark" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>';  
       
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