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
    $this->gplusPage = get_option('katGplusPage');
    $this->linkedinPage = get_option('katLinkedinPage');
    $this->instagramPage = get_option('katInstagramPage');
    //$this->helper = new FacebookRedirectLoginHelper('http://permesso.spade.be/' );

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
