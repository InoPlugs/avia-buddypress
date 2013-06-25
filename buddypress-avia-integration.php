<?php
/**
 *  Plugin Name: BuddyPress Integration for Avia Themes
 *  Plugin URI: http://kriesi.at
 *  Description: Helps you to euse BuddyPress with the latest Avia Themes (Enfold and newer themes)
 *  Author: InoPlugs / Devin Vinson
 *  Version: 1.0
 *  Author URI: -
 */

define( 'AVIA_BUDDYPRESS_URLPATH', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__ ),'',plugin_basename(__FILE__)) );
define( 'AVIA_BUDDYPRESS_URIPATH', plugin_dir_path( __FILE__ ) );

$avia_buddypress = new avia_buddypress();

class avia_buddypress
{
        function __construct()
        {
            //if( !function_exists('bp_is_active') ) return;

            $this->avia_buddypress_opt_name = 'avia_buddypress_theme';
            $this->themes = array('Enfold', 'Propulsion');

            add_action('init', array(&$this,'init_avia_buddypress'));
            add_action('admin_menu', array(&$this,'register_admin_page'));
        }

        function __destruct()
        {
            unset($this->avia_buddypress_opt_name);
        }

        function init_avia_buddypress()
        {
            if(!defined('AVIA_BUDDYPRESS_THEME'))
            {
                add_filter('woocommerce_general_settings','avia_buddypress_option');
                $selected_theme = get_option($this->avia_buddypress_opt_name);
            }
            else
            {
                $selected_theme = AVIA_BUDDYPRESS_THEME;
            }

            $selected_theme = !empty($selected_theme) ? $selected_theme : 'enfold';

            wp_register_style( 'avia-buddypress-css', AVIA_BUDDYPRESS_URLPATH.$selected_theme.'.css', array('avia-woocommerce-css'), '1', 'screen' );
            wp_enqueue_style('avia-buddypress-css');



            /*
            * All Themes - General Section
            */



            /*
             * Specific styles
             */
            if($selected_theme == 'enfold')
            {
                /*
                specific code for enfold...
                 */
            }
        }


        function register_admin_page()
        {
            add_options_page( 'BuddyPress Integration', 'BuddyPress Integration', 'manage_options', 'avia-buddypress-option', array(&$this, 'avia_buddypress_options') );
        }


        function avia_buddypress_options()
        {
            if ( !current_user_can( 'manage_options' ) )  {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }

            if(!empty($_REQUEST[$this->avia_buddypress_opt_name]))
            {
                $setting = htmlspecialchars($_REQUEST[$this->avia_buddypress_opt_name]);
                update_option($this->avia_buddypress_opt_name, $setting);

                echo '<div id="message" class="updated fade"><p>Options Updates</p></div>';
            }
            else
            {
                $setting = get_option($this->avia_buddypress_opt_name);
            }
            ?>
                <div class="wrap">
                <?php screen_icon(); ?>
                <h2>Buddypress Integration for Avia Themes</h2>
                <br />
                <div class="settings-field">
                    <fieldset>
                            <legend>Select the Avia theme which you want to use with BuddyPress:</legend>
                            <form method="post" action="">
                                <select name="<?php echo $this->avia_buddypress_opt_name; ?>">
                                <?php
                                print_r($_POST);
                                foreach ($this->themes as $theme)
                                {
                                    $selected = (strtolower($theme) == $setting) ? 'selected="selected"' : '';
                                    echo '<option '.$selected.' value="'.strtolower($theme).'">'.$theme.'</option>';
                                }
                                ?>
                                </select>

                                <p class="submit">
                                    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                                </p>
                            </form>
                    </fieldset>
                </div>
            <?php
        }
}
