<?php
if( ! class_exists('BeRocket_popup_display') ) {
    class BeRocket_popup_display {
        public static $elements = array();
        public static $load_popup = false;
        function __construct() {
            add_action('wp_footer', array($this, 'wp_footer'), 9);
            add_action('wp_footer', array($this, 'wp_footer2'), 90000);
            add_action('admin_footer', array($this, 'wp_footer'), 9);
            add_action('admin_footer', array($this, 'wp_footer2'), 90000);
            $open_types = array('click', 'page_open', 'scroll_px', 'scroll_block', 'leave_page', 'event');
            foreach($open_types as $open_type) {
                add_filter('BeRocket_popup_open_type_'.$open_type, array($this, 'popup_open_type_'.$open_type), 10, 5);
            }
        }
        public static function include_assets() {
            self::$load_popup = true;
        }
        public static function add_popup($options, $html = '', $popup_open = false) {
            self::include_assets();
            if( count(self::$elements) ) {
                $max_id = array_keys(self::$elements);
                $max_id = max($max_id);
                $max_id++;
            } else {
                $max_id = 1;
            }
            self::$elements[$max_id] = array(
                'popup_open' => $popup_open,
                'options' => $options,
                'html'    => $html
            );
            return $max_id;
        }
        public function wp_footer() {
            if( self::$load_popup ) {
                wp_register_script(
                    'berocket_framework_popup',
                    plugins_url( '../assets/popup/br_popup.js', __FILE__ ),
                    array( 'jquery' )
                );
                wp_register_style(
                    'berocket_framework_popup-animate',
                    plugins_url( '../assets/popup/animate.css', __FILE__ )
                );
                wp_register_style(
                    'berocket_framework_popup',
                    plugins_url( '../assets/popup/br_popup.css', __FILE__ ),
                    array('berocket_framework_popup-animate')
                );
                wp_enqueue_script( 'berocket_framework_popup' );
                wp_enqueue_style( 'berocket_framework_popup-animate' );
                wp_enqueue_style( 'berocket_framework_popup' );
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
                    $element_id = 'br_popup_'.$element_i;
                    //ADD BLOCK WITH CONTENT
                    $page_elements['html_content'] .= '<div id="'.$element_id.'" style="display: none!important;">'.$element['html'].'</div>';
                    //ADD SCRIPT TO INIT POPUP
                    $page_elements['ajax_update'] .= '
                    jQuery("#'.$element_id.'").br_popup('.json_encode($element['options']).');';
                    //ADD SCRIPT FOR BUTTONS
                    if( ! empty($element['popup_open']) && is_array($element['popup_open']) && count($element['popup_open']) ) {
                        if( ! empty($element['popup_open']['type']) ) {
                            $element['popup_open'] = array($element['popup_open']);
                        }
                        foreach($element['popup_open'] as $popup_open) {
                            if( is_array($popup_open) && ! empty($popup_open['type']) ) {
                                $page_elements = apply_filters('BeRocket_popup_open_type_'.$popup_open['type'], $page_elements, $popup_open, $element, $element_i, $element_id);
                            }
                        }
                    }
                }
                $page_elements = apply_filters('BeRocket_popup_open_page_elements', $page_elements, self::$elements);
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
        public function popup_open_type_click($page_elements, $popup_open, $element, $element_i, $element_id) {
            if( ! empty($popup_open['selector']) ) {
                $page_elements['ajax_update'] .= '
                if( ! jQuery("'.$popup_open['selector'].'").data("br_popup_event") ) {
                    jQuery("'.$popup_open['selector'].'").data("br_popup_event", true);
                    jQuery("'.$popup_open['selector'].'").on("click", function(event) {
                        event.preventDefault();
                        jQuery("#'.$element_id.'").br_popup().open_popup();
                    });
                }';
            }
            return $page_elements;
        }
        public function popup_open_type_page_open($page_elements, $popup_open, $element, $element_i, $element_id) {
            $popup_open_script = 'jQuery("#'.$element_id.'").br_popup().open_popup();';
            if( empty($popup_open['timer']) ) {
                $page_elements['page_load'] .= $popup_open_script;
            } else {
                $page_elements['page_load'] .= 'setTimeout(function() {'.$popup_open_script.'}, '.$popup_open['timer'].');';
            }
            return $page_elements;
        }
        public function popup_open_type_scroll_px($page_elements, $popup_open, $element, $element_i, $element_id) {
            $popup_open = array_merge(array(
                'scroll' => '0'
            ), $popup_open);
            $function_name = 'berocket_popup_open_type_scroll_px_'.$element_i.'_'.$popup_open['scroll'];
            $page_elements['page_load'] .= '
            function '.$function_name.'() {
                if( ! jQuery("#'.$element_id.'").data("scroll_px_'.$popup_open['scroll'].'") && jQuery(document).scrollTop() > '.$popup_open['scroll'].' ) {
                    jQuery("#'.$element_id.'").br_popup().open_popup();
                    jQuery("#'.$element_id.'").data("scroll_px_'.$popup_open['scroll'].'", true);
                }
            }
            '.$function_name.'();
            jQuery(document).on("scroll", '.$function_name.');';
            return $page_elements;
        }
        public function popup_open_type_scroll_block($page_elements, $popup_open, $element, $element_i, $element_id) {
            $popup_open = array_merge(array(
                'selector' => ''
            ), $popup_open);
            $md5hash = md5($popup_open['selector']);
            $function_name = 'berocket_popup_open_type_scroll_px_'.$element_i.'_'.$md5hash;
            if( ! empty($popup_open['selector']) ) {
                $page_elements['page_load'] .= '
                function '.$function_name.'() {
                    if( jQuery("'.$popup_open['selector'].'").filter(":visible").length && ! jQuery("#'.$element_id.'").data("scroll_block_'.$md5hash.'") ) {
                        var window_pos = jQuery(document).scrollTop()+jQuery(window).height();
                        var block_pos = jQuery("'.$popup_open['selector'].'").filter(":visible").offset().top;
                        if( window_pos > block_pos ) {
                            jQuery("#'.$element_id.'").br_popup().open_popup();
                            jQuery("#'.$element_id.'").data("scroll_block_'.$md5hash.'", true);
                        }
                    }
                }
                '.$function_name.'();
                jQuery(document).on("scroll", '.$function_name.');';
            }
            return $page_elements;
        }
        public function popup_open_type_leave_page($page_elements, $popup_open, $element, $element_i, $element_id) {
            $page_elements['page_load'] .= '
            jQuery(document).mouseleave(function(event){
                if( ! jQuery("#'.$element_id.'").data("leave_page_'.$element_i.'") && event.clientY < 50 ) {
                    jQuery("#'.$element_id.'").br_popup().open_popup();
                    jQuery("#'.$element_id.'").data("leave_page_'.$element_i.'", true);
                }
            });';
            return $page_elements;
        }
        function popup_open_type_event($page_elements, $popup_open, $element, $element_i, $element_id) {
            if( ! empty($popup_open['event']) ) {
                $page_elements['page_load'] .= '
                jQuery(document).on("'.$popup_open['event'].'", function () {
                    jQuery("#'.$element_id.'").br_popup().open_popup();
                });';
            }
            return $page_elements;
        }
    }
    new BeRocket_popup_display();
}
