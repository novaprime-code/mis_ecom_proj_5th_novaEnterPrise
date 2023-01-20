<?php
if( ! class_exists('BeRocket_framework_addon_lib') ) {
    class BeRocket_framework_addon_lib {
        public $addon_file = '';
        public $plugin_name = '';
        public $php_file_name   = '';
        public $absolute_file   = '';
        function __construct() {
            if(empty($this->addon_file)) return;
            $this->absolute_file = $this->addon_file;
            $this->addon_file = explode('addons', $this->addon_file);
            $this->addon_file = array_pop($this->addon_file);
            $active_addons = apply_filters('berocket_addons_active_'.$this->plugin_name, array());
            add_filter('berocket_addons_info_'.$this->plugin_name, array($this, 'addon_info'));
            if( in_array($this->addon_file, $active_addons) ) {
                $this->check_init();
            }
        }
        function check_init() {
            if ( ! empty( $this->php_file_name ) && file_exists( dirname( $this->absolute_file ) . DIRECTORY_SEPARATOR . $this->php_file_name . '.php' ) ) {
                $this->init_active();
            }
        }
        function init_active() {
            include_once(dirname( $this->absolute_file ) . DIRECTORY_SEPARATOR . $this->php_file_name . '.php');
        }
        function get_addon_data() {
            return array(
                'addon_file'    => $this->addon_file,
                'addon_name'    => 'Addon',
                'image'         => plugins_url('/default.png', __FILE__),
            );
        }
        function addon_info($addon_info) {
            $addon_info[] = $this->get_addon_data();
            return $addon_info;
        }
    }
}
