<?php
/**
 * Plugin Name: Contact Data management from Kat
 * Plugin URI: 
 * Description: This plugin adds a Contact Data management page.
 * Version: 
 * Author: Catherine Arnould
 * Author URI: 
 * License: GPL2
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));

new Kat_Contact_Plugin();

class Kat_Contact_Plugin
{
    private $defaults = array(
        'version' => '1.0'
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
    }

    /**
     * 
    */
    public function register_widget()
    {
        include_once(MY_PLUGIN_PATH.'includes/widget.php');

        register_widget('ContactData'); 
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
}
