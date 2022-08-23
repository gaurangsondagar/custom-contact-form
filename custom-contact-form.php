<?php
/*
Plugin Name: Custom Contacts Form
Plugin URI: https://wordpress.org/plugins/duplicate-page/
Description: Duplicate Posts, Pages and Custom Posts using single click.
Author: Gaurang Sondagar
Version: 1.0
Author URI: https://profiles.wordpress.org/
License: GPLv2
Text Domain: custom-contacts-form
*/

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

if (!defined('D_CONTACTS_PLUGIN_DIRNAME')) {
    define('D_CONTACTS_PLUGIN_DIRNAME', plugin_basename(dirname(__FILE__)));
}
if ( !defined( 'D_CONTACTS_PLUGIN_URL' ) ) {
    define( 'D_CONTACTS_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );
}
if ( !defined( 'D_CONTACTS_SITE_URL' ) ) {
    define( 'D_CONTACTS_SITE_URL', get_site_url() );
}
if ( !defined( 'D_CONTACTS_ADMIN_URL' ) ) {
    define( 'D_CONTACTS_ADMIN_URL', admin_url() );
}

require_once '/inc/d-contact-list-table.php';

if (!class_exists('DifferenzContacts')):

    /**
     * Main plugin class.
     *
     * @since 1.0
     *
     * @author  Gaurang Sondagar
     * @access public
     */
    class DifferenzContacts
    {

        /**
         * Primary class constructor.
         *
         * @since 1.0
         * @access public
         */       
        public function __construct() 
        {
            register_activation_hook(__FILE__, array(&$this, 'd_contact_install'));
            add_action('plugins_loaded', array(&$this, 'differenz_contacts_load_text_domain'));
            add_shortcode('differenzcontacts', array(&$this, 'd_contact_form_ui'));
            add_action('wp_enqueue_scripts', array($this, 'd_contact_form_assets'));
            add_action('wp_ajax_save_differenz_contact', array($this, 'save_differenz_contact'));
            add_action('wp_ajax_nopriv_save_differenz_contact', array($this, 'save_differenz_contact'));
        }


        /**
         * Loclization Function.
         *
         * @access public
         * @since 1.0
         *
         */
        public function differenz_contacts_load_text_domain()
        {
            load_plugin_textdomain('differenz-contacts', false, DUPLICATE_PAGE_PLUGIN_DIRNAME.'/languages');
        }

        public function d_contact_install(){
            //installation code

            /*global $wpdb;

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            $table_name = $wpdb->prefix . "differenz_contact";  //get the database table prefix to create my new table

            $sql = "CREATE TABLE $table_name (
              id int(10) unsigned NOT NULL AUTO_INCREMENT,
              name varchar(150) NOT NULL,
              email varchar(200) NOT NULL,
              subject varchar(150) NOT NULL,
              message text NOT NULL,
              created_date datetime NOT NULL,
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

            dbDelta( $sql );*/

        }

        /**
         * Contact Form UI Function.
         *
         * @access public
         * @since 1.0
         *
         */
        public function d_contact_form_ui()
        {
            ob_start();
            require_once '/inc/d-contact-form.php';
            return ob_get_clean();
        }
       
        /**
         * Enqueue Plugin JS and CSS files.
         *
         * @access public
         * @since 1.0
         *
         */
        public function d_contact_form_assets() {

            wp_enqueue_script( 'd-jquery-js', plugins_url( '/js/jquery.min.js', __FILE__ ) );

            wp_register_script('jquery_validator_js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js' );
            wp_enqueue_script('jquery_validator_js');

            wp_register_script('jquery_validator_mthd', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js' );


            wp_enqueue_style( 'd-contact-style', plugins_url( '/css/d_contact_form_style.css', __FILE__ ) );
            wp_enqueue_script( 'd-contact-js', plugins_url( '/js/d_contact_form.js', __FILE__ ) );

             $d_form_js_array = array(
                'adminurl' => admin_url('admin-ajax.php'),
            );
            wp_localize_script( 'd-contact-js', 'DContactJS', $d_form_js_array );

        }


        /**
         * Save Contact Form Data to database.
         *
         * @access public
         * @since 1.0
         *
         */
        public function save_differenz_contact(){
            global $wpdb;
            $output = array();

            $name = isset($_REQUEST['name']) ? sanitize_text_field($_REQUEST['name']) : '';
            $email = isset($_REQUEST['email']) ? sanitize_email($_REQUEST['email']) : '';
            $subject = isset($_REQUEST['subject']) ? sanitize_text_field($_REQUEST['subject']) : '';
            $message = isset($_REQUEST['message']) ? sanitize_textarea_field($_REQUEST['message']) : '';

            $create_date = date('Y-m-d H:i:s');

            if(wp_verify_nonce($_POST['differenz_contact_nonce'], 'differenz-contact-nonce')) {

                $ins_d_contact = $wpdb->insert('wp_differenz_contact', array(
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                    'created_date' => $create_date
                ));

                if($ins_d_contact){

                    $output['msg'] = __('Message Has Been Sent Successfully!', 'differenz-contacts');
                    $output['status'] = 1;

                }

            } else {
                $output['msg'] = __('Something Goes Wrong!', 'differenz-contacts');
                $output['status'] = 0;
            }

        echo json_encode($output);
        exit();

    }   

    }
new DifferenzContacts();
endif;
?>
