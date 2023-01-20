<?php
if( ! class_exists('BeRocket_tooltip_display') ) {
    class BeRocket_tooltip_display {
        public static $elements = array();
        public static $load_tippy = false;
        function __construct() {
            add_action('wp_footer', array($this, 'wp_footer'), 9);
            add_action('wp_footer', array($this, 'wp_footer2'), 900000000);
            add_action('admin_footer', array($this, 'wp_footer'), 9);
            add_action('admin_footer', array($this, 'wp_footer2'), 900000000);
        }
        public static function include_assets() {
            self::$load_tippy = true;
        }
        public static function add_tooltip($options = array(), $html = '', $selector = '') {
            self::include_assets();
            if( count(self::$elements) ) {
                $max_id = array_keys(self::$elements);
                $max_id = max($max_id);
                $max_id++;
            } else {
                $max_id = 1;
            }
            if( ! is_array($options) ) {
                $options = array();
            }
            $options = array_merge(array(
                'allowHTML' => 'true'
            ), $options);
            foreach($options as $option_name => $option_value) {
                if( in_array($option_name, array('content', 'placement', 'animation')) ) {
                    $option_value = json_encode($option_value);
                } elseif( is_bool($option_value) ) {
                    $option_value = ($option_value ? 'true' : 'false');
                } else {
                    $option_value = $option_value;
                }
                $options[$option_name] = $option_value;
            }
            self::$elements[$max_id] = array(
                'options'       => $options,
                'html'          => $html,
                'selector'      => $selector
            );
            return $max_id;
        }
        public function wp_footer() {
            if( self::$load_tippy ) {
                wp_register_script(
                    'berocket_framework_tippy',
                    plugins_url( '../assets/tippy/tippy.min.js', __FILE__ ),
                    array( 'jquery' )
                );
                wp_register_style(
                    'berocket_framework_tippy',
                    plugins_url( '../assets/tippy/tippy.css', __FILE__ )
                );
                wp_register_style(
                    'berocket_framework_popup-animate',
                    plugins_url( '../assets/popup/animate.css', __FILE__ )
                );
                wp_enqueue_script( 'berocket_framework_tippy' );
                wp_enqueue_style( 'berocket_framework_tippy' );
                wp_enqueue_style( 'berocket_framework_popup-animate' );
            }
        }
        public function wp_footer2() {
            if( count(self::$elements) ) {
                $page_elements = array(
                    'html_content'  => '',
                    'ajax_update'   => '',
                    'page_load'     => ''
                );
                $popup_list = array();
                foreach(self::$elements as $element_i => $element) {
                    $element_id = 'br_tooltip_'.$element_i;
                    //ADD BLOCK WITH CONTENT
                    $element['options']['content'] = json_encode($element['html']);
                    //ADD SCRIPT TO INIT POPUP
                    $options = array();
                    foreach($element['options'] as $option_name => $option_value) {
                        $options[] = json_encode($option_name).':'. $option_value;
                    }
                    $options = '{'.implode(',', $options).'}';
                    $page_elements['page_load'] .= '
                    function '.$element_id.'_init () {
                        if( document.querySelector("'.$element['selector'].'") != null && typeof(document.querySelector("'.$element['selector'].'")._tippy) == "undefined" ) {
                            tippy("'.$element['selector'].'", '.$options.');
                        }
                    }
                    '.$element_id.'_init();';
                    $page_elements['ajax_update'] .= '
                    '.$element_id.'_init();';
                }
                $page_elements = apply_filters('BeRocket_tooltip_tippy_page_elements', $page_elements, self::$elements);
                echo $page_elements['html_content'];
                echo '<script>
                    jQuery(document).ready(function() {
                        function berocket_popup_run_script() {
                            '.$page_elements['ajax_update'].'
                        }
                        berocket_popup_run_script();
                        jQuery(document).ajaxComplete(function() {
                            berocket_popup_run_script();
                        });
                        '.$page_elements['page_load'].'
                    });
                </script>';
            }
        }
    }
    new BeRocket_tooltip_display();
}
