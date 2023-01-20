<?php
if( ! class_exists('BeRocket_error_notices') ) {
    class BeRocket_error_notices {
        public function __construct() {
            add_action( "wp_ajax_berocket_error_notices_get", array ( __CLASS__, 'get_plugin_error_ajax' ) );
        }
        public static function add_plugin_error($plugin_id, $message, $data = array()) {
            if( empty($message) || empty($plugin_id) ) return;
            $errors = self::get_plugin_error($plugin_id);
            array_push($errors, array(
                'message'   => $message,
                'data'      => $data,
                'time'      => current_time('timestamp'),
            ));
            self::save_plugin_error($plugin_id, $errors);
        }
        public static function get_plugin_error($plugin_id) {
            $errors = get_option('berocket_plugin_error_'.$plugin_id);
            if( ! is_array($errors) ) $errors = array();
            return $errors;
        }
        public static function save_plugin_error($plugin_id, $errors) {
            if( count($errors) > 200 ) {
                $i = 0;
                foreach($errors as $error_id => $error) {
                    if($i > 100) break;
                    $i++;
                    unset($errors[$error_id]);
                }
            }
            update_option('berocket_plugin_error_'.$plugin_id, $errors, false);
        }
        public static function get_plugin_error_ajax() {
            if ( ! ( current_user_can( 'manage_options' ) ) ) {
                echo __( 'Do not have access for this feature', 'BeRocket_domain' );
                wp_die();
            }
            $plugin_id = br_get_value_from_array($_POST, 'plugin_id');
            if( empty($plugin_id) ) {
                $plugin_id = br_get_value_from_array($_GET, 'plugin_id');
            }
            $plugin_id = sanitize_key($plugin_id);
            $plugin_id = intval($plugin_id);
            if( ! empty($plugin_id) && ( ! empty($_POST['clear_errors']) || ! empty($_GET['clear_errors']) ) ) {
                self::clear_plugin_errors($plugin_id);
            }
            echo self::get_plugin_error_html($plugin_id);
            wp_die();
        }
        public static function clear_plugin_errors($plugin_id) {
            self::save_plugin_error($plugin_id, array());
        }
        public static function get_plugin_error_html($plugin_id) {
            if( empty($plugin_id) ) {
                return '';
            }
            $block_id = $plugin_id;
            $errors = self::get_plugin_error($plugin_id);
            $errors = array_reverse($errors);
            $html = '<h3>Error List</h3>';
            $html .= '<div class="berocket_plugin_errors_list class_'.$block_id.'">';
            if( count($errors) ) {
                foreach($errors as $error) {
                    $html .= '<div class="berocket_plugin_error">
                    <h4><small>'.date('Y-m-d h:i:s',br_get_value_from_array($error, 'time', current_time('timestamp'))) .'</small>'.br_get_value_from_array($error, 'message', 'No error message').'</h4>
                    <div style="display:none;">';
                    if( ! empty($error['data']) ) {
                        $error_data = print_r($error['data'], true);
                        if( is_array($error['data']) ) {
                            $error_data = substr($error_data, 8);
                            $error_data = substr($error_data, 0, -2);
                        }
                        $html .= '<pre>'.$error_data.'</pre>';
                    }
                    $html .= '</div>
                    </div>';
                }
                $html .= '<button value="'.$plugin_id.'" type="button" class="button berocket_clear_errors_notices">Clear errors for plugin</button>';
            } else {
                $html .= '<div class="berocket_plugin_error"><h4>Plugin doesn\'t have any errors</h4></div>';
            }
            $html .= '</div><script>
                jQuery(".berocket_plugin_errors_list.class_'.$block_id.' .berocket_plugin_error h4").on("click", function() {
                    jQuery(this).next().toggle();
                });
                jQuery(".berocket_plugin_errors_list.class_'.$block_id.' .berocket_clear_errors_notices").on("click", function() {
                    var plugin_id = jQuery(this).val();
                    var $this = jQuery(this);
                    jQuery.post(ajaxurl, {action:"berocket_error_notices_get", plugin_id:plugin_id, clear_errors: true}, function(data) {
                        $this.parents(".berocket_plugin_errors_list").first().html(jQuery("<div>"+data+"</div>").find(".berocket_plugin_errors_list").html());
                    }); 
                });
            </script>
            <style>
                .berocket_plugin_errors_list .berocket_plugin_error:first-child{
                    border-top: 2px solid rgb(153, 153, 153);
                }
                .berocket_plugin_errors_list .berocket_plugin_error {
                    border: 2px solid rgb(153, 153, 153);
                    border-top: 0;
                    background-color: rgb(238, 238, 238);
                    line-height: 1.1em;
                }
                .berocket_plugin_errors_list .berocket_plugin_error > h4 {
                    font-size: 18px;
                    cursor: pointer;
                    margin: 0;
                    padding: 5px;
                    background-color: white;
                }
                .berocket_plugin_errors_list .berocket_plugin_error > h4 small {
                    font-size: 12px;
                    margin-right: 1em;
                }
                .berocket_plugin_errors_list .berocket_plugin_error > div {
                    font-size: 12px;
                    line-height: 1.1em;
                }
                .berocket_clear_errors_notices {
                    margin-top: 1em!important;
                }
            </style>';
            return $html;
        }
    }
    new BeRocket_error_notices();
}
