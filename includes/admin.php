<?php

if(!defined('ABSPATH')) exit; //exit if accessed directly

class My_Plugin_Admin {

    function __construct() {
        /*2 options: we can add the admin page directly in the admin menu, or under the settings admin menu */

        //Page title, menu title, access, slug, function for page output, icon

        add_menu_page(__('Contact data', 'plugin'), __('Contact data', 'plugin'), 'administrator', 'contact_data', array( $this,'my_plugin_settings_page'), 'dashicons-admin-generic');

        //add_submenu_page( 'contact_data', __('Map data'), __('Map data'), 'administrator','map_data', 'my_custom_submenu_page_callback');
        $map_page = add_submenu_page( 'contact_data', __('Map data'), __('Map data'), 'administrator', 'map_data', array( $this,'map_data_sub') );

        //add_submenu_page('contact_data', __('Map data', 'plugin'), __('Map data', 'plugin'), 'contact_data', array($this, 'my_plugin_settings_page2'));

        add_action( 'admin_init', array($this, 'my_plugin_settings') );
        add_action( 'admin_print_scripts-'.$map_page, array($this, 'load_admin_scripts'));



    }
    
    function load_admin_scripts( ) {
      wp_enqueue_style('wp-color-picker');
      wp_enqueue_script('myplugin-script', plugins_url('includes/js/script.js', dirname(__FILE__)), array('wp-color-picker'), false, true );
    }

    //1. Let wp know what we want to register 

    function my_plugin_settings() {
        register_setting( 'my-plugin-settings-group', 'katCompany' );
        register_setting( 'my-plugin-settings-group', 'katAddress' );
        register_setting( 'my-plugin-settings-group', 'katZipTown' );
        register_setting( 'my-plugin-settings-group', 'katCountry' );
        register_setting( 'my-plugin-settings-group', 'katPhone' );
        register_setting( 'my-plugin-settings-group', 'katFax' );
        register_setting( 'my-plugin-settings-group', 'katEmail' );
        register_setting( 'my-plugin-settings-group', 'katFbPage' );
        register_setting( 'my-plugin-settings-group', 'kat_facebook_app_id' );
        register_setting( 'my-plugin-settings-group', 'kat_facebook_app_secret' );
        register_setting( 'my-plugin-settings-group', 'katYoutubePage' );
        register_setting( 'my-plugin-settings-group', 'katTwitterPage' );
        register_setting( 'my-plugin-settings-group', 'katGplusPage' );
        register_setting( 'my-plugin-settings-group', 'katLinkedinPage' );
        register_setting( 'my-plugin-settings-group', 'katInstagramPage' );
        register_setting( 'map-parameters-group', 'katWaterColor' );
        register_setting( 'map-parameters-group', 'katWaterLabelColor' );
        register_setting( 'map-parameters-group', 'katWaterLabelStrokeColor' );
        register_setting( 'map-parameters-group', 'katlandscapeColor' );
        register_setting( 'map-parameters-group', 'katLandscapeLabelColor' );
        register_setting( 'map-parameters-group', 'katHighwayColor' );
        register_setting( 'map-parameters-group', 'katRoadColor' );
        register_setting( 'map-parameters-group', 'katRoadStrokeColor' );
        register_setting( 'map-parameters-group', 'katRoadIconsLabelsColor' );
        register_setting( 'map-parameters-group', 'katAdministrativeLabelsColor' );
        register_setting( 'map-parameters-group', 'katPoiColor' );
        register_setting( 'map-parameters-group', 'katParksColor' );
        register_setting( 'map-parameters-group', 'katPoiLabelColor' );
        register_setting( 'map-parameters-group', 'katPoiLabelStrokeColor' );

    }

    //Settings page

    function my_plugin_settings_page() {?>
     <div class="wrap">
        <h2><?php _e('Contact data', 'plugin'); ?></h2>
         <?php
            if (isset($_POST["submit"])) {

                foreach ($_POST as $keyname=>$value) {
                    if($keyname != 'submit')
                        update_option($keyname, $value);
                }
                echo '<div id="setting-error-settings_updated" class="updated settings-error"> 
<p><strong>'. __('Settings saved', 'plugin').'.</strong></p></div>';
            }
        ?>
        <form method="post" action="">
            <?php 
            //settings_fields(): add the option group as the first parameter. This outputs some hidden fields WordPress will use to save your data.
            settings_fields( 'my-plugin-settings-group' ); 

            //Use the option names you defined while registering them in the name parameter of the inputs
            do_settings_sections( 'my-plugin-settings-group' ); 

            //Grab the value of a field using the get_option() function, passing it the option name as the first parameter:
            ?>
            <table class="form-table">
                <tr valign="top">
                <th scope="row"><?php _e('Company', 'plugin'); ?></th>
                <td><input type="text" name="katCompany" value="<?php echo esc_attr( get_option('katCompany') ); ?>" /></td>
                </tr>
                 
                <tr valign="top">
                <th scope="row"><?php _e('Address', 'plugin'); ?></th>
                <td><input type="text" name="katAddress" value="<?php echo esc_attr( get_option('katAddress') ); ?>" /></td>
                </tr>
                                
                <tr valign="top">
                <th scope="row"><?php _e('Zip and town', 'plugin'); ?></th>
                <td><input type="text" name="katZipTown" value="<?php echo esc_attr( get_option('katZipTown') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Country', 'plugin'); ?></th>
                <td><input type="text" name="katCountry" value="<?php echo esc_attr( get_option('katCountry') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Phone', 'plugin'); ?></th>
                <td><input type="text" name="katPhone" value="<?php echo esc_attr( get_option('katPhone') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Fax', 'plugin'); ?></th>
                <td><input type="text" name="katFax" value="<?php echo esc_attr( get_option('katFax') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Email', 'plugin'); ?></th>
                <td><input type="text" name="katEmail" value="<?php echo esc_attr( get_option('katEmail') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Facebook page', 'plugin'); ?></th>
                <td><input type="text" name="katFbPage" value="<?php echo esc_attr( get_option('katFbPage') ); ?>" /></td>
                </tr>
                <tr valign="top">
                <th scope="row"><?php _e('Facebook App ID', 'plugin'); ?></th>
                <td><input type="text" name="kat_facebook_app_id" value="<?php echo esc_attr( get_option('kat_facebook_app_id') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Facebook App secret', 'plugin'); ?></th>
                <td><input type="text" name="kat_facebook_app_secret" value="<?php echo esc_attr( get_option('kat_facebook_app_secret') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Twitter page', 'plugin'); ?></th>
                <td><input type="text" name="katTwitterPage" value="<?php echo esc_attr( get_option('katTwitterPage') ); ?>" /></td>
                </tr>

                <tr valign="top">
                <th scope="row"><?php _e('Youtube page', 'plugin'); ?></th>
                <td><input type="text" name="katYoutubePage" value="<?php echo esc_attr( get_option('katYoutubePage') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Google plus page', 'plugin'); ?></th>
                <td><input type="text" name="katGplusPage" value="<?php echo esc_attr( get_option('katGplusPage') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Linkedin page', 'plugin'); ?></th>
                <td><input type="text" name="katLinkedinPage" value="<?php echo esc_attr( get_option('katLinkedinPage') ); ?>" /></td>
                </tr>
                
                <tr valign="top">
                <th scope="row"><?php _e('Instagram page', 'plugin'); ?></th>
                <td><input type="text" name="katInstagramPage" value="<?php echo esc_attr( get_option('katInstagramPage') ); ?>" /></td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
         
        </form>
        </div>
    <?php
    }
    function map_data_sub() {
        
        settings_fields( 'map-parameters-group' ); 

            //Use the option names you defined while registering them in the name parameter of the inputs
        do_settings_sections( 'section_page_type' ); 

        ?>

        <h2><?php _e('Map data', 'plugin'); ?></h2>
        <p class="description"><?php _e('I don\'t like plugins injecting small files in the html, not good for optimization, so you have to add some stuff manually.', 'plugin');?></p>
        <p class="description"><?php _e('For a custom icon: just add 2 icons called <strong>markerIcon.png</strong> and <strong>markerIcon.svg</strong> in your theme\'s "images" folder:', 'plugin');?></p>
        <p class="description"><?php _e('For the css: just add this in your css and customize:', 'plugin');?></p>
        <pre>
            #map{
              height:450px;
              @media(min-width:768px){    
                height:600px;
              }
            }
            .infoBox{
              background-color: rgba(40, 36, 32, 0.5);
              color:#fff;
              font-size:14px;
              padding:10px;
              margin-bottom: 40px;
              max-width: 300px;
              white-space: nowrap;
              h6{
                margin:0; float: left; margin-right:10px;max-width:280px
              }
            }
            .infoBox h6{
              color:#fff;margin:0 10px 5px 0; float: left; margin-right:10px;max-width:280px;
              }
            .infoBox .chicon-close{cursor: pointer;content:'&times';float:right;}
            .gmapLink{float:right;width:24px; height:24px}

            img[src*="maps.gstatic.com"], /* 1 */
            img[src*="googleapis.com"] { max-width: none; }
        </pre>

        <?php
            if (isset($_POST["submit"])) {

                foreach ($_POST as $keyname=>$value) {
                    if($keyname != 'submit')
                        update_option($keyname, $value);
                }
                echo '<div id="setting-error-settings_updated" class="updated settings-error"> 
<p><strong>'. __('Settings saved', 'plugin').'.</strong></p></div>';
            }
        ?>
        <form method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Water', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katWaterColor" value="<?php echo esc_attr( get_option('katWaterColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Water labels', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katWaterLabelColor" value="<?php echo esc_attr( get_option('katWaterLabelColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Landscape', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katlandscapeColor" value="<?php echo esc_attr( get_option('katlandscapeColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Landscape labels', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katLandscapeLabelColor" value="<?php echo esc_attr( get_option('katLandscapeLabelColor') ); ?>">
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Roads', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katRoadColor" value="<?php echo esc_attr( get_option('katRoadColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Roads strokes', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katRoadStrokeColor" value="<?php echo esc_attr( get_option('katRoadStrokeColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Highways', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katHighwayColor" value="<?php echo esc_attr( get_option('katHighwayColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Administrative labels', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katAdministrativeLabelsColor" value="<?php echo esc_attr( get_option('katAdministrativeLabelsColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Heuuu. Green?', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katPoiColor" value="<?php echo esc_attr( get_option('katPoiColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Greens labels', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katPoiLabelColor" value="<?php echo esc_attr( get_option('katPoiLabelColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Greens labels strokes', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katPoiLabelStrokeColor" value="<?php echo esc_attr( get_option('katPoiLabelStrokeColor') ); ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Parks', 'plugin'); ?></th>
                    <td>
                        <input type="text" class="color-field" name="katParksColor" value="<?php echo esc_attr( get_option('katParksColor') ); ?>">
                    </td>
                </tr>
                 
            </table>
            
            <?php submit_button(); ?>
         
        </form>
        </div>
    <?

    }
}

?>