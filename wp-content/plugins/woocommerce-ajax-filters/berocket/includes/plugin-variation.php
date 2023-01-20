<?php
if( ! class_exists('BeRocket_plugin_variations') ) {
    class BeRocket_plugin_variations {
        public $version_number = 0;
        public $plugin_name;
        public $values, $info, $defaults;
        public function __construct() {
            add_filter('brfr_plugin_version_capability_'.$this->plugin_name, array($this, 'plugin_version_capability'), $this->version_number, 2);
            add_filter('brfr_plugin_defaults_value_'.$this->plugin_name, array($this, 'default_values'), $this->version_number, 2);
            add_filter('brfr_data_' . $this->plugin_name, array($this, 'settings_page'), $this->version_number);
            add_filter('brfr_tabs_info_' . $this->plugin_name, array($this, 'settings_tabs'), $this->version_number);
        }
        public function plugin_version_capability($plugin_version_capability, $object) {
            $this->info = $object->info;
            $this->values = $object->values;
            $plugin_version_capability = $this->version_number;
            $this->plugin_init();
            return $plugin_version_capability;
        }
        public function default_values($defaults, $object) {
            if( ! is_array($this->defaults) ) {
                $this->defaults = array();
            }
            if( is_array($defaults) ) {
                $defaults = array_merge($this->defaults, $defaults);
            } else {
                $defaults = $this->defaults;
            }
            return $defaults;
        }
        public function plugin_init() {
        }
        public function settings_page($data) {
            return $data;
        }
        public function settings_tabs($data) {
            return $data;
        }
    }
}
