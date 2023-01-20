<?php
class BeRocket_framework_sale_style {
    function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'add_style'));
    }
    function add_style() {
        if( time() > 1637841600 and time() < 1637841600+302400 ) {
            wp_register_style(
                'BeRocket_framework_sale_style',
                plugins_url( 'friday.css', __FILE__ ),
                "",
                BeRocket_Framework::$framework_version
            );
            wp_enqueue_style( 'BeRocket_framework_sale_style' );
        }
        if( time() > 1637841600+302400 and time() < 1637841600+302400+518400 ) {
            wp_register_style(
                'BeRocket_framework_sale_style',
                plugins_url( 'cyber.css', __FILE__ ),
                "",
                BeRocket_Framework::$framework_version
            );
            wp_enqueue_style( 'BeRocket_framework_sale_style' );
        }
    }
}
new BeRocket_framework_sale_style();