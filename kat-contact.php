<?php
/**
 * Plugin Name: Contact Data management from Kat
 * Plugin URI: 
 * Description: This plugin adds a Contact Data management page and some Facebook data (Likes, etc.). New: easily stylable google maps support from your address. 
 * Version: 2
 * Author: Catherine Arnould
 * Author URI: 
 * License: GPL2
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));

add_shortcode('display-map', 'display_map');

function display_map() {
    include_once(MY_PLUGIN_PATH.'includes/map.php');
    $map = new Kat_Map;
    return $map::init();
}

new Kat_Contact_Plugin();

class Kat_Contact_Plugin
{
    private $defaults = array(
        'version' => '1.1'
    );


    public function __construct()
    {
        register_activation_hook(__FILE__, array(&$this, 'activation'));
        register_deactivation_hook(__FILE__, array(&$this, 'deactivation'));

        //update plugin version
        update_option('my_plugin_version', $this->defaults['version'], '', 'no');

        //actions
        add_action('plugins_loaded', array(&$this, 'load_textdomain'));
        add_action('admin_menu', array(&$this,'my_plugin_admin'));
        add_action('widgets_init', array(&$this, 'register_widget'));
        add_action( 'wp_enqueue_scripts', array( &$this, 'map_scripts' ) );
    }
    /**
     * 
    */
    public function register_widget()
    {
        include_once(MY_PLUGIN_PATH.'includes/widget.php');

        register_widget('ContactData'); 
        register_widget('FacebookJoin'); 
        register_widget('FacebookLike'); 
    }


    /**
     * Execution of plugin activation function
    */
    public function activation()
    {
        add_option('my_plugin_version', $this->defaults['version'], '', 'no');
    }


    /**
     * Execution of plugin deactivation function
    */
    public function deactivation()
    {
        delete_option('my_plugin_version');
    }
    
    /**
     * Loads textdomain
    */
    public function load_textdomain()
    {
        load_plugin_textdomain('plugin', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
    }

    function my_plugin_admin() {
        include_once(MY_PLUGIN_PATH.'includes/admin.php');
        $admin = new My_Plugin_Admin;
    }


    public function map_scripts()
    {
        wp_register_script('gmaps', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'));
        wp_enqueue_script('gmaps');
        wp_register_script('infobox', plugins_url('includes/js/infobox.js', __FILE__), array('jquery'));
        wp_enqueue_script('infobox');
    }
}
?>