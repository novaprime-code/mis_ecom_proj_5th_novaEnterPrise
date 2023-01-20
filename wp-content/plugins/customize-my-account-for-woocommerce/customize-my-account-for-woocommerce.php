<?php
/*
    Plugin Name: Customize My Account for WooCommerce
    Plugin URI: https://sysbasics.com
    Description: Customize My account page. Add/Edit/Remove Endpoints.
    Version: 1.3.3
    Author: SysBasics
    Author URI: https://sysbasics.com
    Domain Path: /languages
    Requires at least: 4.0
    Tested up to: 6.1.1
    WC requires at least: 4.0
    WC tested up to: 7.2.2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly




if( !defined( 'wcmamtx_plugin_slug' ) )
    define( 'wcmamtx_plugin_slug', 'customize-my-account-for-woocommerce' );

if( !defined( 'wcmamtx_PLUGIN_URL' ) )
    define( 'wcmamtx_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


if( !defined( 'wcmamtx_PLUGIN_name' ) )
    define( 'wcmamtx_PLUGIN_name', esc_html__( 'Customize My Account' ,'customize-my-account-for-woocommerce') );

if( !defined( 'wcmamtx_update_doc_url' ) )
    define( 'wcmamtx_update_doc_url', 'https://sysbasics.com/knowledge-base/how-to-update-woocommerce-color-or-image-variation-swatches-plugin/' );

if( !defined( 'wcmamtx_doc_url' ) )
    define( 'wcmamtx_doc_url', 'https://sysbasics.com/knowledge-base/' );

if( !defined( 'pro_url' ) )
    define( 'pro_url', 'https://sysbasics.com/go/customize/' );

$mt_type = 'all';



load_plugin_textdomain( 'customize-my-account-for-woocommerce', false, basename( dirname(__FILE__) ).'/languages' );


//include the classes
include dirname( __FILE__ ) . '/include/admin/admin_settings.php';
include dirname( __FILE__ ) . '/include/frontend/woocommerce_frontend_endpoint.php';
include dirname( __FILE__ ) . '/include/frontend/add_content_frontend_login.php';
include dirname( __FILE__ ) . '/include/wcmamtx_extra_functions.php';
include dirname( __FILE__ ) . '/include/gs-envato-item-shortcode.php';
include dirname( __FILE__ ) . '/lib/sysbasics/plugin-deactivation-survey/deactivate-feedback-form.php';


if (!function_exists('wcmamtx_placeholder_img_src')) {
    function wcmamtx_placeholder_img_src() {
        return ''.wcmamtx_PLUGIN_URL.'assets/images/placeholder.png';
    }

}




add_filter('sysbasics_deactivate_feedback_form_plugins', function($plugins) {

    $plugins[] = (object)array(
        'slug'      => wcmamtx_plugin_slug,
        'version'   => wcmamtx_get_plugin_version_number()
    );

    return $plugins;

});


/**
 * Get woocommerce version 
 */

if (!function_exists('wcmamtx_get_woo_version_number')) {

    function wcmamtx_get_woo_version_number() {
       
       if ( ! function_exists( 'get_plugins' ) )
         require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    
       
       $plugin_folder = get_plugins( '/' . 'woocommerce' );
       $plugin_file = 'woocommerce.php';
    
    
       if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
          return $plugin_folder[$plugin_file]['Version'];

       } else {
    
        return NULL;
       }
    }
}


/**
 * Get woocommerce version 
 */

if (!function_exists('wcmamtx_get_plugin_version_number')) {

    function wcmamtx_get_plugin_version_number() {
       
       if ( ! function_exists( 'get_plugins' ) )
         require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    
       
       $plugin_folder = get_plugins( '/' . ''.wcmamtx_plugin_slug.'' );
       $plugin_file = ''.wcmamtx_plugin_slug.'.php';
    
    
       if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
          return $plugin_folder[$plugin_file]['Version'];

       } else {
    
        return NULL;
       }
    }
}

register_activation_hook( __FILE__, 'wcmamtx_subscriber_check_activation_hook' );

if (!function_exists('wcmamtx_subscriber_check_activation_hook')) {

    function wcmamtx_subscriber_check_activation_hook() {
        set_transient( 'wcmamtx-admin-notice-activation', true, 5 );
    }
}




if (!function_exists('wcmamtx_plugin_add_settings_link')) {

    function wcmamtx_plugin_add_settings_link( $links ) {

        $mt_type = wcmamtx_get_version_type();

        $settings_link1 = '<a href="' . admin_url( '/admin.php?page=wcmamtx_advanced_settings' ) . '">' . esc_html__( 'Settings','customize-my-account-for-woocommerce' ) . '</a>';

        array_push( $links, $settings_link1 );

        if ( isset($mt_type) && ($mt_type == "specific")) {
            $settings_link2 = '<a href="'.wcmamtx_update_doc_url.'">' . esc_html__( 'Enable dashboad updates','customize-my-account-for-woocommerce' ) . '</a>';
            array_push( $links, $settings_link2 );
        } else {
            $settings_link2 = '<a href="'.pro_url.'" style="color:green; font-weight:bold;">' . esc_html__( 'Upgrade to premium version','customize-my-account-for-woocommerce' ) . '</a>';
            array_push( $links, $settings_link2 );
        }

        
        return $links;
    }
}

$plugin = plugin_basename( __FILE__ );

add_filter( "plugin_action_links_$plugin", 'wcmamtx_plugin_add_settings_link' );

if (!function_exists('wcmamtx_plugin_row_meta')) {
    function wcmamtx_plugin_row_meta( $links, $file ) {    
        if ( plugin_basename( __FILE__ ) == $file ) {
            $row_meta = array(
                'docs'    => '<a href="' . esc_url( wcmamtx_doc_url ) . '" target="_blank" aria-label="' . esc_attr__( 'Docs', 'customize-my-account-for-woocommerce' ) . '" style="color:green;">' . esc_html__( 'Docs', 'customize-my-account-for-woocommerce' ) . '</a>',
                'support'    => '<a href="' . esc_url( 'https://sysbasics.com/support/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Support', 'customize-my-account-for-woocommerce' ) . '" style="color:green;">' . esc_html__( 'Support', 'customize-my-account-for-woocommerce' ) . '</a>'
            );
            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }
}

add_filter( 'plugin_row_meta', 'wcmamtx_plugin_row_meta', 10, 2 );


if( !defined( 'wcmamtx_version_type' ) )
    define( 'wcmamtx_version_type', $mt_type );


if (!function_exists('wcmamtx_plugin_path')) {

    function wcmamtx_plugin_path() {
  
       return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

}


if (!function_exists('wcmamtx_get_version_type')) {

    function wcmamtx_get_version_type() {
        $plugin_path = plugin_dir_path( __FILE__ );

        if ((strpos($plugin_path, 'pro') !== false) && ( wcmamtx_version_type == "specific")) { 
            $dt_type = 'specific';
        } else {
            $dt_type = 'all';
        }
    
        return $dt_type;
    }
}

$mt_type = wcmamtx_get_version_type();

add_action( 'admin_notices', 'wcmamtx_subscriber_check_activation_notice' );

if (!function_exists('wcmamtx_subscriber_check_activation_notice')) {

    function wcmamtx_subscriber_check_activation_notice(){
        
        if ( get_transient( 'wcmamtx-admin-notice-activation' ) && isset($mt_type) && ($mt_type == "specific")) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html__( 'Thanks for purchasing '.wcmamtx_PLUGIN_name.'.To enable dashboard updates ', 'customize-my-account-for-woocommerce' ); ?> <a href="<?php echo wcmamtx_update_doc_url; ?>"><?php echo esc_html__( 'Follow this', 'customize-my-account-for-woocommerce' ); ?></a>.</p>
            </div>
            <?php
            delete_transient( 'wcmamtx-admin-notice-activation' );
        }
    }
}
?>