<?php

if(!defined('ABSPATH')) exit; //exit if accessed directly

class My_Plugin_Admin {

    function __construct() {
        /*2 options: we can add the admin page directly in the admin menu, or under the settings admin menu */

        //Page title, menu title, access, slug, function for page output, icon

        add_menu_page(__('Contact data', 'plugin'), __('Contact data', 'plugin'), 'administrator', 'contact_data', array( $this,'my_plugin_settings_page'), 'dashicons-admin-generic');
        //add_options_page(__('Contact data', 'plugin'), __('Contact data', 'plugin'), 'manage_options', 'contact_data', array($this, 'my_plugin_settings_page2'));

        add_action( 'admin_init', array($this, 'my_plugin_settings') );


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
        register_setting( 'my-plugin-settings-group', 'katTwitterPage' );
        register_setting( 'my-plugin-settings-group', 'katGplusPage' );
        register_setting( 'my-plugin-settings-group', 'katLinkedinPage' );
        register_setting( 'my-plugin-settings-group', 'katInstagramPage' );
    }

    //Settings page

    function my_plugin_settings_page() {?>
     <div class="wrap">
        <h2><?php _e('Contact data', 'plugin'); ?></h2>
         
        <form method="post" action="options.php">
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

    function my_plugin_settings_page2() {?>
     <div class="wrap">
        <h2><?php _e('Contact data', 'plugin'); ?></h2>
         
        <form method="post" action="options.php">
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
}
?>