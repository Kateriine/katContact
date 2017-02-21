<?php
/**
 * Plugin Name: Identity Data management from Kat
 * Plugin URI: 
 * Description: This plugin adds an Identity Data management page: contact data, social data (Likes, etc.). New: WPML support, Flicker pics display, Twitter search display. 
 * Version: 3
 * Author: Catherine Arnould
 * Author URI: 
 * License: GPL2
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
$katContact = new Kat_Contact_Plugin();

add_shortcode('display-map', 'display_map');

function display_map() {
    include_once(MY_PLUGIN_PATH.'includes/map.php');
    return $map->init();
}

function display_facebook($numberofStatuses=2, $numberofCols=2) {
    include_once(MY_PLUGIN_PATH.'includes/facebook.php');
    $facebook = new KatFacebook(); 
    return $facebook->get_fbFeed($numberofStatuses, $numberofCols);
}

function display_flickr($numberofPics=2, $numberofCols=2, $displayTitle = false, $flickrFeed = '') {
    include_once(MY_PLUGIN_PATH.'includes/flickr.php');
   
    $flickr = new Flickr_Feed($numberofPics, $numberofCols, $displayTitle, $flickrFeed);
    return $flickr->init();
}

function display_twitter($numOfTweets=3, $hashtag='') {
    include_once(MY_PLUGIN_PATH.'includes/twitter_feed.php');
    $twitter = new KatTwitter(); 
    return $twitter->getTSearch($numOfTweets, $hashtag);
}

function get_twitter_count($numOfTweets=999999, $link='') {
    include_once(MY_PLUGIN_PATH.'includes/twitter_feed.php');
    $twitter = new KatTwitter(); 
    return $twitter->getTwitterShareCount();
}

function get_fb_count($post_id) {
    include_once(MY_PLUGIN_PATH.'includes/facebook.php');
    $facebook = new KatFacebook(); 
    return $facebook->get_fbShare($post_id);
}

function display_youtube($numberofVids=2, $numberofCols=2, $displayTitle=false, $youtubeFeed='') {
    include_once(MY_PLUGIN_PATH.'includes/youtube.php');
    $youtube = new Youtube($numberofVids, $numberofCols, $displayTitle, $youtubeFeed); 
    return $youtube->init();
}

function display_instagram($numberofPics=2, $numberofCols=2) {
    include_once(MY_PLUGIN_PATH.'includes/instagram.php');
    $instagram = new Instagram_Feed($numberofPics, $numberofCols); 
    return $instagram->init();
}

function display_social_share() {
    include_once(MY_PLUGIN_PATH.'includes/social.php');
    return social_count();
}

function getKatCompany(){
    return apply_filters( 'wpml_translate_single_string', get_option("katCompany"), 'KatContact Data', 'katCompany' );
}
function getKatAddress(){
    return apply_filters( 'wpml_translate_single_string', get_option("katAddress"), 'KatContact Data', 'katAddress' );
}
function getKatZipTown(){
    return apply_filters( 'wpml_translate_single_string', get_option("katZipTown"), 'KatContact Data', 'katZipTown' );
}
function getKatCountry(){
    return apply_filters( 'wpml_translate_single_string', get_option("katCountry"), 'KatContact Data', 'katCountry' );
}
function getKatPhone(){
    return apply_filters( 'wpml_translate_single_string', get_option("katPhone"), 'KatContact Data', 'katPhone' );
}
function getKatFax(){
    return apply_filters( 'wpml_translate_single_string', get_option("katFax"), 'KatContact Data', 'katCompany' );
}
function getKatEmail(){
    return apply_filters( 'wpml_translate_single_string', get_option("katEmail"), 'KatContact Data', 'katEmail' );
}
function getKatFbPage(){
    return apply_filters( 'wpml_translate_single_string', get_option("katFbPage"), 'KatContact Data', 'katFbPage' );
}
function getKatTwitterPage(){
    return apply_filters( 'wpml_translate_single_string', get_option("katTwitterPage"), 'KatContact Data', 'katTwitterPage' );
}
function getKatYoutubePage(){
    return apply_filters( 'wpml_translate_single_string', get_option("katYoutubePage"), 'KatContact Data', 'katYoutubePage' );
}
function getKatGplusPage(){
    return apply_filters( 'wpml_translate_single_string', get_option("katGplusPage"), 'KatContact Data', 'katGplusPage' );
}
function getKatLinkedinPage(){
    return apply_filters( 'wpml_translate_single_string', get_option("katLinkedinPage"), 'KatContact Data', 'katLinkedinPage' );
}
function getKatInstagramPage(){
    return apply_filters( 'wpml_translate_single_string', get_option("katInstagramPage"), 'KatContact Data', 'katInstagramPage' );
}
function getKatFlickrPage(){
    return apply_filters( 'wpml_translate_single_string', get_option("katFlickrPage"), 'KatContact Data', 'katFlickrPage' );
}
function getKatPinterestPage(){
    return apply_filters( 'wpml_translate_single_string', get_option("katPinterestPage"), 'KatContact Data', 'katPinterestPage' );
}


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
    }

    /**
     * 
    */
    public function register_widget()
    {
        include_once(MY_PLUGIN_PATH.'includes/widget.php');

        register_widget('SocialData'); 
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


    public function map_scripts() {
        wp_register_script('gmaps', 'http://maps.google.com/maps/api/js?sensor=false', array('jquery'));
        wp_enqueue_script('gmaps');
        wp_register_script('infobox', plugins_url('includes/js/infobox.js', __FILE__), array('jquery'));
        wp_enqueue_script('infobox');
    }
}
?>