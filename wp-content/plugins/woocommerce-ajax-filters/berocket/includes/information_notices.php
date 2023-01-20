<?php
/*new berocket_information_notices(array(
    'start' => 1497880000, // timestamp when notice start
    'end'   => 1497885000, // timestamp when notice end
    'name'  => 'name', //notice name must be unique for this time period
    'html'  => '', //text or html code as content of notice
    'righthtml'  => '<a class="berocket_no_thanks">No thanks</a>', //content in the right block, this is default value. This html code must be added to all notices
    'rightwidth'  => 80, //width of right content is static and will be as this value. berocket_no_thanks block is 60px and 20px is additional
    'nothankswidth'  => 60, //berocket_no_thanks width. set to 0 if block doesn't uses. Or set to any other value if uses other text inside berocket_no_thanks
    'contentwidth'  => 400, //width that uses for mediaquery is image_width + contentwidth + rightwidth
    'subscribe'  => false, //add subscribe form to the righthtml
    'priority'  => 20, //priority of notice. 1-5 is main priority and displays on settings page always
    'height'  => 50, //height of notice. image will be scaled
    'repeat'  => false, //repeat notice after some time. time can use any values that accept function strtotime
    'repeatcount'  => 1, //repeat count. how many times notice will be displayed after close
    'image'  => array(
        'global' => 'http://berocket.com/images/logo-2.png', //image URL from other site. Image will be copied to uploads folder if it possible
        //'local' => 'http://wordpress-site.com/wp-content/uploads/logo-2.png', //notice will be used this image directly
    ),
));*/
//delete_option('berocket_information_notices'); //remove all notice information
//delete_option('berocket_last_close_notices_time'); //remove wait time before next notice
//delete_option('berocket_admin_notices_rate_stars');
if( ! class_exists( 'berocket_information_notices' ) ) {
    /**
     * Class berocket_admin_notices
     */

    class berocket_information_notices {
        public static $notice_index = 10000;
        public static $default_notice_options = array(
                'name'          => 'sale',
                'html'          => '',
                'righthtml'     => '<a class="berocket_no_thanks">No thanks</a>',
                'rightwidth'    => 80,
                'nothankswidth' => 60,
                'contentwidth'  => 400,
                'closed'        => '0',
                'priority'      => 20,
                'height'        => 50,
                'image'         => array(
                    'global'    => 'http://berocket.com/images/logo-2.png'
                ),
            );
        function __construct($options = array()) {
            if( ! is_admin() ) return;
            $options = array_merge(self::$default_notice_options, $options);
            self::set_notice_by_path($options);
        }
        public static function set_notice_by_path($options, $replace = false, $find_names = false) {
            $notices = get_option('berocket_information_notices');
            if( ! is_array($notices) ) {
                $notices = array();
            }
            if( empty($options['image']) || (empty($options['image']['local']) && empty($options['image']['global'])) ) {
                $options['image'] = array('width' => 0, 'height' => 0, 'scale' => 0);
            } else {
                $file_exist = false;
                if( ! $file_exist ) {
                    if( ! empty($options['image']['local']) ) {
                        $img_local = $options['image']['local'];
                        $img_local = str_replace(site_url('/'), '', $img_local);
                        $img_local = ABSPATH . $img_local;
                        $file_exist = ( file_exists($img_local) );
                    } else {
                        $file_exist = false;
                    }
                }
                if( $file_exist ) {
                    $check_size = true;
                    if( isset($current_notice['image']['local']) && $current_notice['image']['local'] == $options['image']['local'] ) {
                        if( isset($current_notice['image']['width']) && isset($current_notice['image']['height']) ) {
                            $options['image']['width'] = $current_notice['image']['width'];
                            $options['image']['height'] = $current_notice['image']['height'];
                            $check_size = false;
                        }
                    }
                    if( $check_size ) {
                        $image_size = @ getimagesize($options['image']['local']);
                        if( ! empty($image_size[0]) && ! empty($image_size[1]) ) {
                            $options['image']['width'] = $image_size[0];
                            $options['image']['height'] = $image_size[1];
                        } else {
                            $options['image']['width'] = $options['height'];
                            $options['image']['height'] = $options['height'];
                        }
                    }
                    $options['image']['scale'] = $options['height'] / $options['image']['height'];
                } else {
                    $options['image'] = array('width' => 0, 'height' => 0, 'scale' => 0);
                }
            }
            $notices[$options['name']] = $options;
            update_option('berocket_information_notices', $notices);
            return true;
        }
        public static function get_notices() {
            $notices = get_option('berocket_information_notices');
            return $notices;
        }
        public static function display_notice() {
            $notices = self::get_notices();
            if( is_array($notices) && count($notices) > 0 ) {
                foreach($notices as $notice) {
                    if( is_array($notice) ) {
                        self::echo_notice($notice);
                    }
                }
            }
        }
        public static function echo_notice($notice) {
            $notice = array_merge(self::$default_notice_options, $notice);
            $settings_page = apply_filters('is_berocket_settings_page', false);
            self::$notice_index++;
            $notice_data = array(
                'name'      => $notice['name'],
            );
            if( ! empty($notice['subscribe']) ) {
                $user_email = wp_get_current_user();
                if( isset($user_email->user_email) ) {
                    $user_email = $user_email->user_email;
                } else {
                    $user_email = '';
                }
                $notice['righthtml'] = 
                '<form class="berocket_subscribe_form" method="POST" action="' . admin_url( 'admin-ajax.php' ) . '">
                    <input type="hidden" name="berocket_action" value="berocket_subscribe_email">
                    <input class="berocket_subscribe_email" type="email" name="email" value="' . $user_email . '">
                    <input type="submit" class="button-primary button berocket_notice_submit" value="Subscribe">
                </form>' . $notice['righthtml'];
                $notice['rightwidth'] += 300;
            }
            echo '
                <div class="notice berocket_admin_notice berocket_admin_notice_', self::$notice_index, '" data-notice=\'', json_encode($notice_data), '\'>',
                    ( empty($notice['image']['local']) ? '' : '<img class="berocket_notice_img" src="' . $notice['image']['local'] . '">' ),
                    ( empty($notice['righthtml']) ? '' :
                    '<div class="berocket_notice_right_content">
                        <div class="berocket_notice_content">' . $notice['righthtml'] . '</div>
                        <div class="berocket_notice_after_content"></div>
                    </div>' ),
                    '<div class="berocket_notice_content_wrap">
                        <div class="berocket_notice_content">', $notice['html'], '</div>
                        <div class="berocket_notice_after_content"></div>
                    </div></div>';
            echo '<style>
                .berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' {
                    height: ', $notice['height'], 'px;
                    padding: 0;
                    min-width: ', max($notice['image']['width'] * $notice['image']['scale'], $notice['rightwidth']), 'px;
                    border-left: 0 none;
                    border-radius: 3px;
                    overflow: hidden;
                    box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.2);
                }
                .berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_notice_img {
                    height: ', $notice['height'], 'px;
                    width: ', ($notice['image']['width'] * $notice['image']['scale']), 'px;
                    float: left;
                }
                .berocket_admin_notice .berocket_notice_content_wrap {
                    margin-left: ', ($notice['image']['width'] * $notice['image']['scale'] + 5), 'px;
                    margin-right: ', ($notice['rightwidth'] <= 20 ? 0 : $notice['rightwidth'] + 15), 'px;
                    box-sizing: border-box;
                    height: ', $notice['height'], 'px;
                    overflow: auto;
                    overflow-x: hidden;
                    overflow-y: auto;
                    font-size: 16px;
                    line-height: 1em;
                    text-align: center;
                }
                .berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_notice_right_content {',
                    ( $notice['rightwidth'] <= 20 ? ' display: none' :
                    'height: ' . $notice['height'] . 'px;
                    float: right;
                    width: ' . $notice['rightwidth'] . 'px;
                    -webkit-box-shadow: box-shadow: -1px 0 0 0 rgba(0, 0, 0, 0.1);
                    box-shadow: -1px 0 0 0 rgba(0, 0, 0, 0.1);
                    padding-left: 10px;' ),
                '}
                .berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_no_thanks {
                    cursor: pointer;
                    color: #0073aa;
                    opacity: 0.5;
                    display: inline-block;
                }
                @media screen and (min-width: 783px) and (max-width: ', round($notice['image']['width'] * $notice['image']['scale'] + $notice['rightwidth'] + $notice['contentwidth'] + 10 + 200), 'px) {
                    div.berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_notice_content_wrap {
                        font-size: 14px;
                    }
                    div.berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_button {
                        padding: 4px 15px;
                    }
                }
                @media screen and (max-width: 782px) {
                    div.berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_notice_content_wrap {
                        margin-left: 0;
                        margin-right: 0;
                        clear: both;
                        height: initial;
                    }
                    div.berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_notice_content {
                        line-height: 2.5em;
                    }
                    div.berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_notice_content .berocket_button {
                        line-height: 1em;
                    }
                    div.berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' {
                        height: initial;
                        text-align: center;
                        padding: 20px;
                    }
                    .berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_notice_img {
                        float: none;
                        display: inline-block;
                    }
                    div.berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_notice_right_content {
                        display: block;
                        float: none;
                        clear: both;
                        width: 100%;
                        -webkit-box-shadow: none;
                        box-shadow: none;
                        padding: 0;
                    }
                }
            </style>
            <script>
                jQuery(document).ready(function() {
                    jQuery(document).on("click", ".berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_no_thanks", function(event){
                        event.preventDefault();
                        var notice = jQuery(this).parents(".berocket_admin_notice.berocket_admin_notice_', self::$notice_index, '").data("notice");
                        jQuery.post(ajaxurl, {action:"berocket_information_close_notice", notice:notice}, function(data){});
                        jQuery(this).parents(".berocket_admin_notice.berocket_admin_notice_', self::$notice_index, '").hide();
                    });
                });';
            echo '</script>';
            berocket_admin_notices::echo_styles();
            berocket_admin_notices::echo_jquery_functions();
        }
        public static function close_notice($notice = FALSE) {
            if ( ! ( current_user_can( 'manage_options' ) ) ) {
                echo __( 'Do not have access for this feature', 'BeRocket_domain' );
                wp_die();
            }
            if( ( $notice == FALSE || ! is_array($notice) ) && ! empty($_POST['notice']) ) {
                $notice = sanitize_textarea_field($_POST['notice']);
            }
            $notices = self::get_notices();
            if( empty($notice) || empty($notice['name']) ) {
                $notices = array();
            } elseif( isset($notices[$notice['name']]) ) {
                unset($notices[$notice['name']]);
            }
            update_option('berocket_information_notices', $notices);
            wp_die();
        }
    }
    add_action( 'admin_notices', array('berocket_information_notices', 'display_notice') );
    add_action( 'wp_ajax_berocket_information_close_notice', array('berocket_information_notices', 'close_notice') );
}
?>
