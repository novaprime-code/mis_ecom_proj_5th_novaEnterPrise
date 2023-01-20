<?php
if ( ! class_exists('BeRocket_framework_template_lib') ) {
    class BeRocket_framework_template_lib {
        public $template_file   = '';
        public $plugin_name     = '';
        public $active_template = '';
        public $css_file_name   = '';
        public $js_file_name    = '';
        public $absolute_file   = '';

        function __construct() {
            if ( empty( $this->template_file ) ) {
                return;
            }

            $this->absolute_file = $this->template_file;
            $this->template_file = explode( 'style_templates', $this->template_file );
            $this->template_file = array_pop( $this->template_file );
            $active_templates    = apply_filters( 'berocket_templates_active_' . $this->plugin_name, array() );

            add_filter( 'berocket_templates_info_' . $this->plugin_name, array( $this, 'template_info' ) );

            if ( $this->template_file == $active_templates ) {
                add_filter( 'berocket_selected_templates_info_' . $this->plugin_name, array( $this, 'template_info' ) );
                add_action( 'berocket_init_template_' . $this->plugin_name, array( $this, 'check_active' ) );
            }
        }

        function check_active( $template_activate ) {
            if ( $template_activate == $this->template_file ) {
                $this->init_active();
            }
        }

        function init_active() {
            if ( ! empty( $this->css_file_name ) && file_exists( dirname( $this->absolute_file ) . DIRECTORY_SEPARATOR . $this->css_file_name . '.css' ) ) {
                add_action( 'wp_footer', array( $this, 'enqueue_styles' ), 5 );
            }

            if ( ! empty( $this->js_file_name ) && file_exists( dirname( $this->absolute_file ) . DIRECTORY_SEPARATOR . $this->js_file_name . '.js' ) ) {
                add_action( 'wp_footer', array( $this, 'enqueue_scripts' ), 5 );
            }
        }

        function enqueue_styles() {
            $style_name = 'berocket_' . $this->plugin_name . '_' . $this->css_file_name;
            wp_register_style( $style_name, plugins_url( '/' . $this->css_file_name . '.css', $this->absolute_file ) );
            wp_enqueue_style( $style_name );
        }

        function enqueue_scripts() {
            $script_name = 'berocket_' . $this->plugin_name . '_' . $this->js_file_name;
            wp_register_script( $script_name, plugins_url( '/' . $this->js_file_name . '.js', $this->absolute_file ) );
            wp_enqueue_script( $script_name );
        }

        function get_template_data() {
            return array(
                'template_file' => $this->template_file,
                'template_name' => 'Template',
                'image'         => plugins_url( '/default.png', __FILE__ ),
                'class'         => 'template1',
                'instance'      => $this,
                'paid'          => false
            );
        }

        function template_info( $template_info ) {
            $template_data                                      = $this->get_template_data();
            $template_info[ $template_data[ 'template_file' ] ] = $template_data;

            return $template_info;
        }
    }
}
