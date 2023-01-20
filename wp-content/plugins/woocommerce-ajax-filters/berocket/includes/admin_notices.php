<?php
/*new berocket_admin_notices(array(
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
//delete_option('berocket_admin_notices'); //remove all notice information
//delete_option('berocket_last_close_notices_time'); //remove wait time before next notice
//delete_option('berocket_admin_notices_rate_stars');
if( ! class_exists( 'berocket_admin_notices' ) ) {
    /**
     * Class berocket_admin_notices
     */

    class berocket_admin_notices {
        public $find_names, $notice_exist = false;
        public static $last_time = '-24 hours';
        public static $end_soon_time = '+1 hour';
        public static $subscribed = false;
        public static $jquery_script_exist = false;
        public static $styles_exist = false;
        public static $notice_index = 0;
        public static $default_notice_options = array(
                'start'         => 0,
                'end'           => 0,
                'name'          => 'sale',
                'html'          => '',
                'righthtml'     => '<a class="berocket_no_thanks">No thanks</a>',
                'rightwidth'    => 80,
                'nothankswidth' => 60,
                'contentwidth'  => 400,
                'subscribe'     => false,
                'closed'        => '0',
                'priority'      => 20,
                'height'        => 50,
                'repeat'        => false,
                'repeatcount'   => 1,
                'image'         => array(
                    'global'    => 'http://berocket.com/images/logo-2.png'
                ),
            );
        function __construct($options = array()) {
            if( ! is_admin() ) return;
            $options = array_merge(self::$default_notice_options, $options);
            self::set_notice_by_path($options);
        }
        public static function sort_notices($notices) {
            return self::sort_array (
                $notices,
                array(
                    1 => 'krsort',
                    2 => 'ksort',
                    3 => 'ksort'
                ),
                array(
                    '1' => SORT_NUMERIC,
                    '2' => SORT_NUMERIC,
                    '3' => SORT_NUMERIC
                )
            );
        }
        public static function sort_array($array, $sort_functions, $options, $count = 3) {
            if( $count > 0 ) {
                if( ! is_array($array) ) {
                    return array();
                }
                $call_function = $sort_functions[$count];
                $call_function($array, $options[$count]);
                if( isset($array[0]) ) {
                    $first_element = $array[0];
                    unset($array[0]);
                    $array[0] = $first_element;
                    unset($first_element);
                }
                foreach($array as $item_id => $item) {
                    if( $count == 2 ) {
                        $time = time();
                        if( $item_id < $time && $item_id != 0 ) {
                            unset($array[$item_id]);
                        } else {
                            $array[$item_id] = self::sort_array($item, $sort_functions, $options, $count - 1);
                        }
                    } else {
                        $array[$item_id] = self::sort_array($item, $sort_functions, $options, $count - 1);
                    }
                    if( isset($array[$item_id]) && ( ! is_array($array[$item_id]) || count($array[$item_id]) == 0 ) ) {
                        unset($array[$item_id]);
                    }
                }
            }
            return $array;
        }
        public static function get_notice_by_path($find_names) {
            $notices = get_option( 'berocket_admin_notices' );
            if ( ! is_array( $notices ) ) {
                $notices = array();
            }

            $current_notice = &$notices;
            foreach ( $find_names as $find_name ) {
                if ( isset( $current_notice[ $find_name ] ) ) {
                    $new_current_notice = &$current_notice[ $find_name ];
                    unset( $current_notice );
                    $current_notice = &$new_current_notice;
                    unset( $new_current_notice );
                } else {
                    unset( $current_notice );
                    break;
                }
            }

            if ( ! isset( $current_notice ) ) $current_notice = false;

            return $current_notice;
        }
        public static function berocket_array_udiff_assoc_notice($a1, $a2) {
            return json_encode($a1) > json_encode($a2);
        }
        public static function set_notice_by_path($options, $replace = false, $find_names = false) {
            self::$subscribed = get_option('berocket_email_subscribed');
            if( self::$subscribed && $options['subscribe'] ) {
                return false;
            }
            $notices = get_option('berocket_admin_notices');
            if( $options['end'] < time() && $options['end'] != 0 ) {
                return false;
            }
            if( $find_names === false ) {
                $find_names = array($options['priority'], $options['end'], $options['start'], $options['name']);
            }
            if( ! is_array($notices) ) {
                $notices = array();
            }

            $current_notice = &$notices;
            foreach($find_names as $find_name) {
                if( ! isset($current_notice[$find_name]) ) {
                    $current_notice[$find_name] = array();
                }
                $new_current_notice = &$current_notice[$find_name];
                unset($current_notice);
                $current_notice = &$new_current_notice;
                unset($new_current_notice);
            }
            $array_diff = array_udiff_assoc($options, $current_notice, array(__CLASS__, 'berocket_array_udiff_assoc_notice'));
            if( isset($array_diff['image']) ) {
                unset($array_diff['image']);
            }

            if( count($array_diff) == 0 ) {
                return true;
            }
            if( empty($options['image']) || (empty($options['image']['local']) && empty($options['image']['global'])) ) {
                $options['image'] = array('width' => 0, 'height' => 0, 'scale' => 0);
            } else {
                $file_exist = false;
                if( isset($options['image']['global']) ) {
                    $wp_upload = wp_upload_dir();
                    if( ! isset($options['image']['local']) ) {
                        $url_global = $options['image']['global'];
                        $img_local = $wp_upload['basedir'] . '/' . basename($url_global);
                        $url_local = $wp_upload['baseurl'] . '/' . basename($url_global);
                        if( ! file_exists($img_local) && is_writable($wp_upload['path']) ) {
                            file_put_contents($img_local, file_get_contents($url_global));
                        }
                        if( file_exists($img_local) ) {
                            $options['image']['local'] = $url_local;
                            $options['image']['pathlocal'] = $img_local;
                        } else {
                            $options['image']['local'] = $url_global;
                            $file_exist = true;
                        }
                    }
                }
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
            if( count($current_notice) == 0 ) {
                $current_notice = $options;
            } else {
                if( ! empty($options['image']['local']) && $options['image']['local'] != $current_notice['image']['local'] ) {
                    if( isset($current_notice['image']['pathlocal']) ) {
                        unlink($current_notice['image']['pathlocal']);
                    }
                }
                if( ! $replace ) {
                    $options['closed'] = $current_notice['closed'];
                }
                $current_notice = $options;
            }
            $notices = self::sort_notices($notices);
            update_option('berocket_admin_notices', $notices);
            return true;
        }
        public static function get_notice() {
            $notices = get_option('berocket_admin_notices');
            $last_time = get_option('berocket_last_close_notices_time');
            self::$subscribed = get_option('berocket_email_subscribed');
            if( ! is_array($notices) || count($notices) == 0 ) return false;
            if( $last_time > strtotime(self::$last_time) ) {
                $current_notice = self::get_not_closed_notice($notices, true);
            } else {
                $current_notice = self::get_not_closed_notice($notices);
            }
            update_option('berocket_current_displayed_notice', $current_notice);
            return $current_notice;
        }
        public static function get_notice_for_settings() {
            $notices = get_option('berocket_admin_notices');
            $last_notice = get_option('berocket_admin_notices_last_on_options');
            self::$subscribed = get_option('berocket_email_subscribed');
            $notices = self::get_notices_with_priority($notices);
            if( ! is_array($notices) || count($notices) == 0 ) {
                return false;
            }
            if( $last_notice === false ) {
                $last_notice = 0;
            } else {
                $last_notice++;
            }
            if( count($notices) <= $last_notice ) {
                $last_notice = 0;
            }
            update_option('berocket_admin_notices_last_on_options', $last_notice);
            $notice = $notices[$last_notice];
            return $notice;
        }
        public static function get_not_closed_notice($array, $end_soon = false, $closed = 0, $count = 3) {
            $notice = false;
            if( empty($array) || ! is_array($array) ) {
                $array = array();
            }
            $time = time();
            foreach($array as $item_id => $item) {
                if( $count > 0 ) {
                    if( $count == 2 && $item_id < $time && $item_id != 0 || $count == 1 && $item_id > $time && $item_id != 0 ) {
                        continue;
                    }
                    if( $count == 2 && $item_id < strtotime(self::$end_soon_time) && $item_id != 0 ) {
                        $notice = self::get_not_closed_notice($item, $end_soon, 1, $count - 1);
                    } else {
                        if( $end_soon && $count == 2 ) {
                            break;
                        }
                        $notice = self::get_not_closed_notice($item, $end_soon, $closed, $count - 1);
                    }
                } else {
                    $display_notice = ( $item['closed'] <= $closed && ( ! self::$subscribed || ! $item['subscribe'] ) && ($item['start'] == 0 || $item['start'] < $time) && ($item['end'] == 0 || $item['end'] > $time) );
                    $display_notice = apply_filters( 'berocket_admin_notice_is_display_notice', $display_notice, $item, array(
                        'subscribed' => self::$subscribed,
                        'end_soon'   => $end_soon,
                        'closed'     => $closed,
                    ) );
                    if( $display_notice ) {
                        return $item;
                    }
                }
                if( $notice != false ) break;
            }
            return $notice;
        }
        public static function get_notices_with_priority($array, $priority = 19, $count = 3) {
            if( empty($array) || ! is_array($array) ) {
                $array = array();
            }
            $time = time();
            $notices = array();
            foreach($array as $item_id => $item) {
                if( $count > 0 ) {
                    if( $count == 3 && $item_id > $priority || $count == 2 && $item_id < $time && $item_id != 0 || $count == 1 && $item_id > $time && $item_id != 0 ) {
                        continue;
                    }
                    $notice = self::get_notices_with_priority($item, $priority, $count - 1);
                    $notices = array_merge($notices, $notice);
                } else {
                    $display_notice = ( (!self::$subscribed || ! $item['subscribe']) && ($item['priority'] <= 5 || !$item['closed']) );
                    $display_notice = apply_filters( 'berocket_admin_notice_is_display_notice_priority', $display_notice, $item, array(
                        'subscribed' => self::$subscribed,
                        'priority'   => $priority,
                    ) );
                    if( $display_notice ) {
                        $notices[] = $item;
                    }
                }
            }
            return $notices;
        }
        public static function display_admin_notice() {
            $settings_page = apply_filters('is_berocket_settings_page', false);
            if( $settings_page ) {
                $notice = self::get_notice_for_settings();
            } else {
                $notice = self::get_notice();
            }
            if( ! empty($notice['original']) ) {
                $original_notice = self::get_notice_by_path($notice['original']);
                unset($original_notice['start'], $original_notice['closed'], $original_notice['repeatcount']);
                $notice = array_merge($notice, $original_notice);
            }

            if( $notice !== false ) {
                self::echo_notice($notice);
            }
            $additional_notice = apply_filters('berocket_display_additional_notices', array());
            if( is_array($additional_notice) && count($additional_notice) > 0 ) {
                foreach($additional_notice as $notice) {
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
                'start'     => $notice['start'],
                'end'       => $notice['end'],
                'name'      => $notice['name'],
                'priority'  => $notice['priority'],
            );
            if( $notice['end'] < strtotime(self::$end_soon_time) && $notice['end'] != 0 ) {
                $time_left = $notice['end'] - time();
                $time_left_str = "";
                $time = $time_left;
                if ( $time >= 3600 ) {
                    $hours = floor( $time/3600 );
                    $time  = $time%3600;
                    $time_left_str .= sprintf("%02d", $hours) . ":";
                }
                if ( $time >= 60 || $time_left >= 3600 ) {
                    $minutes = floor( $time/60 );
                    $time  = $time%60;
                    $time_left_str .= sprintf("%02d", $minutes) . ":";
                }

                $time_left_str .= sprintf("%02d", $time);
                $notice['rightwidth'] += 60;
                $notice['righthtml'] .= '<div class="berocket_time_left_block">Left<br><span class="berocket_time_left" data-time="' . $time_left . '">' . $time_left_str . '</span></div>';
            }
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
            if( $settings_page && $notice['priority'] <= 5 ) {
                $notice['rightwidth'] -= $notice['nothankswidth'];
            }
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
                .berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_no_thanks {',
                    ( $settings_page && $notice['priority'] <= 5 ? 'display: none!important;' : 'cursor: pointer;
                    color: #0073aa;
                    opacity: 0.5;
                    display: inline-block;' ),
                '}
                ', ( empty($notice['subscribe']) ? '' : '
                .berocket_admin_notice.berocket_admin_notice_' . self::$notice_index . ' .berocket_subscribe_form {
                    display: inline-block;
                    padding-right: 10px;
                }
                .berocket_admin_notice.berocket_admin_notice_' . self::$notice_index . ' .berocket_subscribe_form .berocket_subscribe_email {
                    width: 180px;
                    margin: 0;
                    height: 28px
                    display: inline;
                }
                .berocket_admin_notice.berocket_admin_notice_' . self::$notice_index . ' .berocket_subscribe_form .berocket_notice_submit {
                    margin: 0 0 0 10px;
                    min-width: 80px;
                    max-width: 80px;
                    width: 80px;
                    padding: 0;
                    display: inline;
                    vertical-align: baseline;
                    color: #fff;
                    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
                    text-shadow: none;
                    border: 0 none;
                    -moz-user-select: none;
                    background: #ff5252 none repeat scroll 0 0;
                    box-sizing: border-box;
                    cursor: pointer;
                    font-size: 14px;
                    outline: 0 none;
                    position: relative;
                    text-align: center;
                    text-decoration: none;
                    transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s, background-color 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s;
                    white-space: nowrap;
                    height: auto;
                }
                .berocket_admin_notice.berocket_admin_notice_' . self::$notice_index . ' .berocket_subscribe_form .berocket_notice_submit:hover,
                .berocket_admin_notice.berocket_admin_notice_' . self::$notice_index . ' .berocket_subscribe_form .berocket_notice_submit:focus,
                .berocket_admin_notice.berocket_admin_notice_' . self::$notice_index . ' .berocket_subscribe_form .berocket_notice_submit:active{
                    background: #ff6e68 none repeat scroll 0 0;
                    color: white;
                }' ), '
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
                        jQuery.post(ajaxurl, {action:"berocket_admin_close_notice", notice:notice}, function(data){});
                        jQuery(this).parents(".berocket_admin_notice.berocket_admin_notice_', self::$notice_index, '").hide();
                    });
                });';
            if( $notice['end'] < strtotime(self::$end_soon_time) && $notice['end'] != 0 ) {
                echo 'setInterval(function(){
                    jQuery(".berocket_admin_notice.berocket_admin_notice_', self::$notice_index, ' .berocket_time_left").each(function(i, o) {
                        var left_time = jQuery(o).data("time");
                        var time = left_time;
                        if( time <= 0 ) {
                            jQuery(o).parents(".berocket_admin_notice.berocket_admin_notice_', self::$notice_index, '").hide();
                        } else {
                            time--;
                            jQuery(o).data("time", time);
                            var str = "";
                            if ( time >= 3600 ) {
                                hours = Math.floor( time/3600 );
                                time  = time%3600;
                                str += ("0" + hours).slice(-2) + ":";
                            }
                            if ( time >= 60 || left_time >= 3600 ) {
                                minutes = Math.floor( time/60 );
                                time  = time%60;
                                str += ("0" + minutes).slice(-2) + ":";
                            }
                            seconds = time;
                            str += ("0" + seconds).slice(-2);
                            jQuery(o).html(str);
                        }
                    });
                }, 1000);';
            }
            echo '</script>';
            self::echo_styles();
            self::echo_jquery_functions();
        }
        public static function echo_styles() {
            if( ! self::$styles_exist ) {
                self::$styles_exist = true;
                echo '<style>
                .berocket_admin_notice .berocket_notice_content {
                    display: inline-block;
                    vertical-align: middle;
                    padding: 2px 5px;
                    max-width: 99%;
                    box-sizing: border-box;
                }
                .berocket_admin_notice .berocket_notice_after_content {
                    display: inline-block;
                    vertical-align: middle;
                    height: 100%;
                    width: 0px;
                }
                .berocket_admin_notice .berocket_no_thanks:hover {
                    opacity: 1;
                }
                .berocket_admin_notice .berocket_time_left_block {
                    display: inline-block;
                    text-align: center;
                    vertical-align: middle;
                    padding: 0 0 0 10px;
                }
                .berocket_notice_content .berocket_button {
                    margin: 0 0 0 10px;
                    min-width: 80px;
                    padding: 6px 16px;
                    vertical-align: baseline;
                    color: #fff;
                    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
                    text-shadow: none;
                    border: 0 none;
                    -moz-user-select: none;
                    background: #ff5252 none repeat scroll 0 0;
                    box-sizing: border-box;
                    cursor: pointer;
                    font-size: 15px;
                    outline: 0 none;
                    position: relative;
                    text-align: center;
                    text-decoration: none;
                    transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s, background-color 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s;
                    white-space: nowrap;
                    height: auto;
                    display: inline-block;
                    font-weight: bold;
                    line-height: 120%;
                }
                </style>';
            }
        }
        public static function echo_jquery_functions() {
            if( ! self::$jquery_script_exist ) {
                self::$jquery_script_exist = true;
                echo '<script>
                    jQuery(document).on("berocket_subscribed", ".berocket_admin_notice", function(){
                        jQuery(this).find(".berocket_no_thanks").click();
                    });
                    jQuery(document).on("berocket_incorrect_email", ".berocket_admin_notice", function(){
                        jQuery(this).find(".berocket_subscribe_form").addClass("form-invalid");
                    });
                    jQuery(document).on("change", ".berocket_admin_notice", function(){
                        jQuery(this).find(".berocket_subscribe_form").removeClass("form-invalid");
                    });
                    var berocket_email_submited = false;
                    jQuery(document).on("submit berocket_subscribe_send", ".berocket_subscribe_form", function(event){
                        event.preventDefault();
                        event.stopPropagation();
                        var $this = jQuery(this);
                        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        var email = $this.find("[name=email]").val();
                        if( ! re.test(email) ) {
                            $this.trigger("berocket_incorrect_email");
                            return false;
                        }
                        if( ! berocket_email_submited ) {
                            berocket_email_submited = true;
                            if( $this.is("form") ) {
                                var data = $this.serialize();
                                data = data+"&action="+$this.find("[name=\'berocket_action\']").val();
                            } else {
                                if( jQuery(".berocket_plugin_id_subscribe").length ) {
                                    var data = {email:email, action: $this.find("[name=\'berocket_action\']").val(), plugin:jQuery(".berocket_plugin_id_subscribe").val()};
                                } else {
                                    var data = {email:email, action: $this.find("[name=\'berocket_action\']").val()};
                                }
                            }
                            var url = $this.attr("action");
                            $this.trigger("berocket_subscribing");
                            jQuery.post(url, data, function(data){
                                $this.trigger("berocket_subscribed");
                            }).fail(function(){
                                $this.trigger("berocket_not_subscribed");
                            });
                        }
                    });
                    jQuery(document).on("berocket_subscribing", ".berocket_subscribe", function(event) {
                        event.preventDefault();
                        jQuery(this).hide();
                    });
                    jQuery(document).on("berocket_incorrect_email", ".berocket_subscribe", function(event) {
                        event.preventDefault();
                        jQuery(this).addClass("form-invalid").find(".error").show();
                    });
                    jQuery(document).on("keyup", ".berocket_subscribe.berocket_subscribe_form .berocket_subscribe_email", function(event) {
                        var keyCode = event.keyCode || event.which;
                        if (keyCode === 13) {
                            event.preventDefault();
                            jQuery(this).parents(".berocket_subscribe_form").trigger("berocket_subscribe_send");
                            return false;
                        }
                    });
                    jQuery(document).on("click", ".berocket_subscribe.berocket_subscribe_form .berocket_notice_submit", function(event) {
                        event.preventDefault();
                        jQuery(this).parents(".berocket_subscribe_form").trigger("berocket_subscribe_send");
                    });

                </script>';
            }
        }
        public static function close_notice($notice = FALSE) {
            if ( ! ( current_user_can( 'manage_options' ) ) ) {
                echo __( 'Do not have access for this feature', 'BeRocket_domain' );
                wp_die();
            }
            self::$subscribed = get_option('berocket_email_subscribed');
            if( ( $notice == FALSE || ! is_array($notice) ) && ! empty($_POST['notice']) ) {
                $notice = sanitize_textarea_field($_POST['notice']);
            }
            if (empty($notice) || ! is_array($notice)
            || (empty($notice['start']) && $notice['start'] !== '0')
            || (empty($notice['end']) && $notice['end'] !== '0')
            || (empty($notice['priority']) && $notice['priority'] !== '0')
            || (empty($notice['name'])) ) {
                $notice = self::get_notice();
            }
            if( empty($notice) || ! is_array($notice) ) {
                wp_die();
            }
            $find_names = array($notice['priority'], $notice['end'], $notice['start'], $notice['name']);
            $current_notice = self::get_notice_by_path($find_names);
            if( isset($current_notice) ) {
                if( $current_notice['end'] < strtotime(self::$end_soon_time) ) {
                    $current_notice['closed'] = 2;
                } else {
                    $current_notice['closed'] = 1;
                }
                if( $current_notice['closed'] < 2 && ! empty($current_notice['repeat']) && ! empty($current_notice['repeatcount']) && ( ! self::$subscribed || ! $current_notice['subscribe'] ) ) {
                    $new_notice = $current_notice;
                    if( empty($current_notice['original']) ) {
                        $new_notice['original'] = $find_names;
                    }
                    $new_notice['repeatcount'] = $current_notice['repeatcount'] - 1;
                    $new_notice['start'] = strtotime($current_notice['repeat']);
                    $new_notice['closed'] = 0;
                    self::set_notice_by_path($new_notice);
                }
                self::set_notice_by_path($current_notice, true);
            }
            update_option('berocket_last_close_notices_time', time());
            wp_die();
        }
        public static function subscribe() {
            if ( ! ( current_user_can( 'manage_options' ) ) ) {
                echo __( 'Do not have access for this feature', 'BeRocket_domain' );
                wp_die();
            }
            if( ! empty($_POST['email']) ) {
                $plugins = array();
                if( ! empty($_POST['plugin']) ) {
                    $plugins[] = sanitize_textarea_field($_POST['plugin']);
                }
                $plugins = apply_filters('berocket_admin_notices_subscribe_plugins', $plugins);
                $plugins = array_unique($plugins);
                $plugins = implode(',', $plugins);
                $email = sanitize_email($_POST['email']);
                update_option('berocket_email_subscribed', true);

                $response = wp_remote_post('https://berocket.com/main/subscribe', array(
                    'body' => array(
                        'subs_email' => $email,
                        'plugins'    => $plugins
                    ),
                    'method' => 'POST',
                    'timeout' => 15,
                    'redirection' => 5,
                    'blocking' => true,
                    'sslverify' => false
                ));
                if( ! is_wp_error($response) ) {
                    $out = wp_remote_retrieve_body($response);
                    echo $out;
                }
            }
            wp_die();
        }
        public static function generate_subscribe_notice() {
            new berocket_admin_notices(array(
                'start' => 0,
                'end'   => 0,
                'name'  => 'subscribe',
                'html'  => 'Subscribe to get latest BeRocket news and updates, plugin recommendations and configuration help, promotional email with discount codes.',
                'subscribe'  => true,
                'image'  => array(
                    'local' => plugin_dir_url( __FILE__ ) . '../assets/images/ad_white_on_orange.webp',
                ),
            ));
        }
    }
    add_action( 'admin_notices', array('berocket_admin_notices', 'display_admin_notice') );
    add_action( 'wp_ajax_berocket_admin_close_notice', array('berocket_admin_notices', 'close_notice') );
    add_action( 'wp_ajax_berocket_subscribe_email', array('berocket_admin_notices', 'subscribe') );
}
if( ! class_exists( 'berocket_admin_notices_rate_stars' ) ) {
    class berocket_admin_notices_rate_stars {
        public $first_time = '+7 days';
        public $later_time = '+7 days';
        function __construct() {
            add_action( 'admin_notices', array($this, 'admin_notices') );
            add_action( 'wp_ajax_berocket_rate_stars_close', array($this, 'disable_rate_notice') );
            add_action( 'wp_ajax_berocket_feature_request_send', array($this, 'feature_request_send') );
            add_action( 'berocket_rate_plugin_window', array($this, 'show_rate_window'), 10, 2 );
            add_action( 'berocket_related_plugins_window', array($this, 'show_related_window'), 10, 3 );
            add_action( 'berocket_above_admin_settings', array($this, 'show_ad_above_admin_settings'), 10, 2 );
            add_action( 'berocket_feature_request_window', array($this, 'show_feature_request_window'), 10, 2 );
        }
        function admin_notices() {
            $display_one = false;
            $disabled = get_option('berocket_admin_notices_rate_stars');
            if( ! is_array($disabled) ) {
                $disabled = array();
            }
            $plugins = apply_filters('berocket_admin_notices_rate_stars_plugins', array());
            foreach($plugins as $plugin_id => $plugin) {
                $display = false;
                if( empty($disabled[$plugin['id']]) ) {
                    $disabled[$plugin['id']] = array(
                        'time' => strtotime($this->first_time),
                        'count' => 0
                    );
                } elseif($disabled[$plugin['id']]['time'] != 0 && $disabled[$plugin['id']]['time'] < time()) {
                    $display = true;
                }
                if( $display ) {
                    $display_one = true;
                    ?>
                    <div class="notice notice-info berocket-rate-stars berocket-rate-stars-block berocket-rate-stars-<?php echo $plugin['id']; ?>">
                        <p><?php
                        $text = __( 'Awesome, you\'ve been using %plugin_name% Plugin for more than 1 week. May we ask you to give it a 5-star rating on WordPress?', 'BeRocket_domain' );
                        $text_mobile = __( 'May we ask you to give our plugin %plugin_name% a 5-star rating?', 'BeRocket_domain' );
                        $plugin['name'] = str_replace(' for WooCommerce', '', $plugin['name']);
                        $text = str_replace('%plugin_name%', '<a href="https://wordpress.org/support/plugin/'.$plugin['free_slug'].'/" target="_blank">'.$plugin['name'].'</a>', $text);
                        $text_mobile = str_replace('%plugin_name%', '<a href="https://wordpress.org/support/plugin/'.$plugin['free_slug'].'/" target="_blank">'.$plugin['name'].'</a>', $text_mobile);
                        $text = '<span class="brfeature_show_mobile">' . $text_mobile.'</span><span class="berocket-right-block">
                            <a class="berocket_rate_close brfirst"
                                data-plugin="'.$plugin['id'].'"
                                data-action="berocket_rate_stars_close"
                                data-prevent="0"
                                data-function="berocket_rate_star_close_notice"
                                data-later="0"
                                data-thanks_html=\'<picture><source type="image/webp" srcset="'.plugin_dir_url( __FILE__ ).'../assets/images/Thank-you.webp" alt="Feature Request"><img src="https://berocket.com/images/plugin/Thank-you.png" style="width: 100%;" alt="Feature Request"></picture><h3 class="berocket_thank_you_rate_us">'.__('Each good feedback is very important for plugin growth', 'BeRocket_domain').'</h3>\'
                                href="https://wordpress.org/support/plugin/'.$plugin['free_slug'].'/reviews/?filter=5#new-post"
                                target="_blank">'.__('Ok, you deserved it', 'BeRocket_domain').'</a>
                            <span class="brfirts"> | </span>
                            <a class="berocket_rate_close brsecond"
                                data-plugin="'.$plugin['id'].'"
                                data-action="berocket_rate_stars_close"
                                data-prevent="1"
                                data-later="1"
                                data-function="berocket_rate_star_close_notice"
                                href="#later">
                                    <span class="brfeature_hide_mobile">'.__('Maybe later', 'BeRocket_domain').'</span>
                                    <span class="brfeature_show_mobile">'.__('Later', 'BeRocket_domain').'</span>
                                </a>
                            <span class="brsecond"> | </span>
                            <a class="berocket_rate_close brthird"
                                data-plugin="'.$plugin['id'].'"
                                data-action="berocket_rate_stars_close"
                                data-prevent="1"
                                data-later="0"
                                data-function="berocket_rate_star_close_notice"
                                href="#close">
                                    <span class="brfeature_hide_mobile">'.__('I already did', 'BeRocket_domain').'</span>
                                    <span class="brfeature_show_mobile">'.__('Already', 'BeRocket_domain').'</span>
                                </a>
                        </span><span class="brfeature_hide_mobile">' . $text.'</span>';
                        echo $text;
                        ?></p>
                    </div>
                    <?php
                }
            }
            if( $display_one ) {
                add_action('admin_footer', array($this, 'wp_footer_js'));
                ?>
                <style>
                    .berocket-rate-stars span.brsecond,
                    .berocket-rate-stars a.brthird {
                        color: #999;
                    }
                    .berocket-rate-stars .berocket-right-block > span {
                        display: inline-block;
                        margin-left: 10px;
                        margin-right: 10px;
                    }
                    .berocket-rate-stars a.brthird:hover {
                        color: #00a0d2;
                    }
                    .berocket-rate-stars a {
                        text-decoration: none;
                    }
                    .berocket-rate-stars .berocket-right-block {
                        float: right;
                        padding-left: 20px;
                        display: inline-block;

                    }
                    .berocket-rate-stars .brfeature_show_mobile {
                        display: none;
                    }
                    @media screen and (min-width: 768px) and (max-width: 1024px) {
                        .berocket-rate-stars .berocket-right-block span.brfirts {
                            display: none;
                        }
                        .berocket-rate-stars .berocket-right-block .berocket_rate_close.brfirst {
                            display: block;
                        }
                    }
                    @media screen and (max-width: 768px) {
                        .berocket-rate-stars {
                            display: none;
                        }
                        .berocket-rate-stars .brfeature_show_mobile {
                            display: inline-block;
                        }
                        .berocket-rate-stars .brfeature_hide_mobile {
                            display: none;
                        }
                        .berocket-rate-stars .berocket-right-block {
                            float: none;
                            padding-left: 0;
                        }
                        .berocket-rate-stars .berocket-right-block > span {
                            margin-left: 5px;
                            margin-right: 5px;
                        }
                    }
                </style>
                <?php
            }
            update_option('berocket_admin_notices_rate_stars', $disabled);
        }
        function disable_rate_notice() {
            if ( ! ( current_user_can( 'manage_options' ) ) ) {
                echo __( 'Do not have access for this feature', 'BeRocket_domain' );
                wp_die();
            }
            $plugin = (empty($_GET['plugin']) ? (empty($_POST['plugin']) ? '' : $_POST['plugin']) : $_GET['plugin']);
            $later = (empty($_GET['later']) ? (empty($_POST['later']) ? '' : $_POST['later']) : $_GET['later']);
            $disabled = get_option('berocket_admin_notices_rate_stars');
            if( isset($disabled[$plugin]) && is_array($disabled[$plugin]) && isset($disabled[$plugin]['time']) ) {
                if( empty($later) ) {
                    $disabled[$plugin]['time'] = 0;
                } else {
                    $disabled[$plugin]['time'] = strtotime($this->later_time);
                }
            }
            update_option('berocket_admin_notices_rate_stars', $disabled);
            wp_die();
        }
        function feature_request_send() {
            if ( ! ( current_user_can( 'manage_options' ) ) ) {
                echo __( 'Do not have access for this feature', 'BeRocket_domain' );
                wp_die();
            }
            $plugin = (empty($_GET['brfeature_plugin']) ? (empty($_POST['brfeature_plugin']) ? '' : $_POST['brfeature_plugin']) : $_GET['brfeature_plugin']);
            $email = (empty($_GET['brfeature_email']) ? (empty($_POST['brfeature_email']) ? '' : $_POST['brfeature_email']) : $_GET['brfeature_email']);
            $title = (empty($_GET['brfeature_title']) ? (empty($_POST['brfeature_title']) ? '' : $_POST['brfeature_title']) : $_GET['brfeature_title']);
            $description = (empty($_GET['brfeature_description']) ? (empty($_POST['brfeature_description']) ? '' : $_POST['brfeature_description']) : $_GET['brfeature_description']);
            if( ! empty($plugin) && ! empty($title) && ! empty($description) ) {
                $response = wp_remote_post( 'https://berocket.com/api/data/add_feature_request', array(
                    'body'        => array(
                        'plugin'        => $plugin,
                        'email'         => $email,
                        'title'         => $title,
                        'description'   => $description
                    ),
                    'method'      => 'POST',
                    'timeout'     => 5,
                    'redirection' => 5,
                    'blocking'    => true,
                    'sslverify'   => false
                ) );
            }
            wp_die();
        }
        function show_rate_window($html, $plugin_id) {
            $disabled = get_option('berocket_admin_notices_rate_stars');
            if( empty($disabled[$plugin_id]) || $disabled[$plugin_id]['time'] != 0 ) {
                $plugins = apply_filters('berocket_admin_notices_rate_stars_plugins', array());
                foreach($plugins as $plugin) {
                    if( $plugin['id'] == $plugin_id ) {
                        $html = '<div class="berocket_rate_plugin berocket-rate-stars-block berocket-rate-stars-plugin-page-'.$plugin['id'].'">
                            <h3>'.__('May we ask you to give us a 5-star feedback?', 'BeRocket_domain').'</h3>
                            <a class="berocket_rate_close brfirst"
                                data-plugin="'.$plugin['id'].'"
                                data-action="berocket_rate_stars_close"
                                data-prevent="0"
                                data-later="0"
                                data-function="berocket_rate_star_close_notice"
                                data-thanks_html=\'<picture><source type="image/webp" srcset="'.plugin_dir_url( __FILE__ ).'../assets/images/Thank-you.webp" alt="Feature Request"><img src="https://berocket.com/images/plugin/Thank-you.png" style="width: 100%;" alt="Feature Request"></picture><h3 class="berocket_thank_you_rate_us">'.__('Each good feedback is very important for plugin growth', 'BeRocket_domain').'</h3>\'
                                href="https://wordpress.org/support/plugin/'.$plugin['free_slug'].'/reviews/?filter=5#new-post"
                                target="_blank">'.__('Ok, you deserved it', 'BeRocket_domain').'</a>
                                <p>'.__('Support the plugin by setting good feedback.<br>We really need this.', 'BeRocket_domain').'</p>
                        </div>
                        <style>
                        .berocket_rate_plugin {
                            border-radius: 3px;
                            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.06);
                            overflow: auto;
                            position: relative;
                            background-color: white;
                            color: rgba(0, 0, 0, 0.87);
                            padding: 0 25px;
                            margin-bottom: 30px;
                            box-sizing: border-box;
                            text-align: center;
                            float: right;
                            clear: right;
                            width: 28%;
                        }
                        .berocket_rate_plugin .berocket_rate_close {
                            margin-top: 30px;
                            margin-bottom: 20px;
                            color: #fff;
                            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
                            text-shadow: none;
                            border: 0 none;
                            min-width: 120px;
                            width: 90%;
                            -moz-user-select: none;
                            background: #ff5252 none repeat scroll 0 0;
                            box-sizing: border-box;
                            cursor: pointer;
                            display: inline-block;
                            font-size: 14px;
                            outline: 0 none;
                            padding: 8px;
                            position: relative;
                            text-align: center;
                            text-decoration: none;
                            transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s, background-color 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s;
                            white-space: nowrap;
                            height: auto;
                            vertical-align: top;
                            line-height: 25px;
                            border-radius: 3px;
                            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
                            font-weight: bold;

                            margin: 5px 0;
                            background: #97b9cf;
                            border: 2px solid #97b9cf;
                            color: white;
                        }
                        .berocket_rate_plugin img {
                            margin-top: 20px;
                        }
                        .berocket_rate_plugin .berocket_thank_you_rate_us {
                            color: #555;
                            margin-bottom: 35px;
                        }
                        .berocket_rate_plugin .berocket_rate_close:hover,
                        .berocket_rate_plugin .berocket_rate_close:focus,
                        .berocket_rate_plugin .berocket_rate_close:active{
                            color: white;
                            background: #87a9bf;
                            border: 2px solid #87a9bf;
                        }
                        @media screen and (min-width: 901px) and (max-width: 1200px) {
                            .berocket_rate_plugin{
                                padding-left: 10px;
                                padding-right: 10px;
                            }
                        }
                        @media screen and (max-width: 900px) {
                            .berocket_rate_plugin {
                                float: none;
                                width: 100%;
                                margin-top: 30px;
                                margin-bottom: 0;
                            }
                            .berocket_rate_plugin .berocket_rate_close{
                                float: none;
                                width: 100%;
                            }
                        }
                        </style>';
                        add_action('admin_footer', array($this, 'wp_footer_js'));
                        return $html;
                    }
                }
            }
            return $html;
        }

        public static function get_plugin_data($plugin_id = false) {
            $host = 'https://berocket.ams3.cdn.digitaloceanspaces.com/plugins/banners/';

            $plugins      = array(
                array(
                    'plugin_id' => 1,
                    'id'        => 1,
                    'price'     => '44',
                    'slug'      => 'ajax_filters',
                    'image'     => $host . 'Filters.png',
                    'image_top' => 'https://e8e3g4v6.rocketcdn.me/wp-content/uploads/2022/11/top-banner-filters.jpg?v=new',
                    'title'     => 'WooCommerce AJAX Products Filter',
                    'desc'      => "Increase conversions by making the product search easier and suitable for your customers' needs",
                    'desc_top'  => 'Get nice URLs and correct variations filtering for your shop with WooCommerce AJAX Products Filter for only ${price}!',
                    'url'       => 'https://berocket.com/woocommerce-ajax-products-filter/?coupon=pfum3vap',
                    'bg'        => 'white',
                    'bg_top'    => 'linear-gradient(to right, #b54df8, #8a46fd 60%)'
                ),
                array(
                    'plugin_id' => 18,
                    'id'        => 35,
                    'price'     => '34',
                    'slug'      => 'products_label',
                    'image'     => $host . 'Labels.png',
                    'image_top' => 'https://e8e3g4v6.rocketcdn.me/wp-content/uploads/2022/11/top-banner-labels.jpg?v=new',
                    'title'     => 'WooCommerce Advanced Product Labels',
                    'desc'      => "Capture client's attention on needed products. Create labels easily and quickly",
                    'desc_top'  => 'Capture client\'s attention on needed products. Create labels easily and quickly for only ${price}!',
                    'url'       => 'https://berocket.com/woocommerce-advanced-product-labels/?coupon=pluwq5cq',
                    'bg'        => '#f2f2f2',
                    'bg_top'    => 'linear-gradient(to right, #b54df8, #8b46fb 50%)'
                ),
                array(
                    'plugin_id' => 2,
                    'id'        => 3,
                    'price'     => '34',
                    'slug'      => 'list_grid',
                    'image'     => $host . 'GridList.png',
                    'image_top' => 'https://e8e3g4v6.rocketcdn.me/wp-content/uploads/2022/11/top-banner-gridlist.jpg?v=new',
                    'title'     => 'WooCommerce Grid/List View',
                    'desc'      => "Users need option to see more info. Add Grid/List toggle and Products per page to show more",
                    'desc_top'  => 'Users need option to see more info. Add Grid/List toggle and Products per page to show more for only ${price}!',
                    'url'       => 'https://berocket.com/woocommerce-grid-list-view/?coupon=gluf79j8',
                    'bg'        => '#5f4a8b',
                    'bg_top'    => 'linear-gradient(to right, #b54df8, #7631fc 60%)'
                ),
                array(
                    'plugin_id' => 3,
                    'id'        => 5,
                    'price'     => '34',
                    'slug'      => 'BeRocket_LMP',
                    'image'     => $host . 'LoadMore.png',
                    'image_top' => 'https://e8e3g4v6.rocketcdn.me/wp-content/uploads/2022/11/top-banner-loadmore.jpg?v=new',
                    'title'     => 'WooCommerce Load More Products',
                    'desc'      => "Load next page' products with infinite scrolling, AJAX pagination or load more products button",
                    'desc_top'  => 'Load next page\' products with infinite scrolling, AJAX pagination or load more products button for only ${price}!',
                    'url'       => 'https://berocket.com/woocommerce-load-more-products/?coupon=lmune6q5',
                    'bg'        => '#5f4a8b',
                    'bg_top'    => 'linear-gradient(to right, #b54df8, #8a46fd 60%)'
                ),
                array(
                    'plugin_id' => 9,
                    'id'        => 17,
                    'price'     => '34',
                    'slug'      => 'MM_Quantity',
                    'image'     => $host . 'MinMax.png',
                    'image_top' => 'https://e8e3g4v6.rocketcdn.me/wp-content/uploads/2022/11/top-banner-minmax.jpg?v=new',
                    'title'     => 'WooCommerce Min/Max Quantity',
                    'desc'      => "Define quantity rules for orders, products and variations. Group the products and limit all of them together",
                    'desc_top'  => 'Define quantity rules for orders, products and variations. Group the products and limit all of them together for only ${price}!',
                    'url'       => 'https://berocket.com/woocommerce-min-max-quantity/?coupon=mmumq2ts',
                    'bg'        => '#f5ebdd',
                    'bg_top'    => 'linear-gradient(to right, #b54df8, #8244fd 55%)'
                ),
                array(
                    'plugin_id' => 10,
                    'id'        => 19,
                    'price'     => '34',
                    'slug'      => 'tab_manager',
                    'image'     => $host . 'Tabs.png',
                    'image_top' => 'https://e8e3g4v6.rocketcdn.me/wp-content/uploads/2022/11/top-banner-tabs.jpg?v=new1',
                    'title'     => 'WooCommerce Product Tabs Manager',
                    'desc'      => "Upgrade your tabs to a powerful marketing instrument. Show there related products or special info.",
                    'desc_top'  => 'Upgrade your tabs to a powerful marketing instrument. Show there related products or special info for only ${price}!',
                    'url'       => 'https://berocket.com/woocommerce-product-tabs-manager/?coupon=tmuufxrb',
                    'bg'        => '#955188',
                    'bg_top'    => 'linear-gradient(to right, #b54df8, #8346fd 60%)'
                ),
                array(
                    'plugin_id' => 14,
                    'id'        => 27,
                    'price'     => '34',
                    'slug'      => 'image_watermark',
                    'image'     => $host . 'Watermark.png',
                    'image_top' => 'https://e8e3g4v6.rocketcdn.me/wp-content/uploads/2022/11/top-banner-watermarks.jpg?v=new',
                    'title'     => 'WooCommerce Products Image Watermark',
                    'desc'      => "Don't let them steal it. Add watermarks to protect your images",
                    'desc_top'  => 'Don\'t let them steal it. Add watermarks to protect your images for only ${price}!',
                    'url'       => 'https://berocket.com/woocommerce-products-image-watermark/?coupon=iwujqp3m',
                    'bg'        => '#c2c3c5',
                    'bg_top'    => 'linear-gradient(to right, #b54df8, #8746fc 60%)'
                ),
            );
            $plugin_ids   = array_column( $plugins, 'plugin_id' );
            $plugins_data = BeRocket_Framework::get_product_data_berocket( implode( '-', $plugin_ids ) );

            if ( is_array( $plugins_data ) ) {
                foreach ( $plugins_data as $plugin_data ) {
                    if ( ! is_array( $plugin_data ) ) {
                        continue;
                    }

                    foreach ( $plugins as &$plugin ) {
                        if ( $plugin[ 'plugin_id' ] == berocket_isset( $plugin_data[ 'id' ] ) && isset( $plugin_data[ 'price' ] ) ) {
                            $plugin[ 'price' ] = $plugin_data[ 'price' ];
                            break;
                        }
                    }
                }
            }

            foreach ( $plugins as & $plugin ) {
                $plugin['desc_top'] = str_replace('{price}', $plugin['price'], $plugin['desc_top']);
            }

            if ( $plugin_id !== false ) {
                foreach ( $plugins as $plugin2 ) {
                    if ( $plugin2[ 'plugin_id' ] == $plugin_id ) {
                        return $plugin2;
                    }
                }

                return false;
            }

            return $plugins;
        }

        function show_ad_above_admin_settings($plugin_version_capability, $cur_plugin) {
            if( $plugin_version_capability < 10 ) {
	            $plugin = self::get_plugin_data($cur_plugin->info['id']);
	            if( $plugin === false ) {
		            $plugin = self::get_plugin_data( 1 );
	            }
                if ( time() > 1637841600 and time() < 1637841600+302400 ) {
                    echo "
                    <div class='berocket-above-settings-banner' style='background: #1a1a1a; padding: 0;'>
                        <a href='{$plugin['url']}?utm_source=free_plugin&utm_medium=settings&utm_campaign={$cur_plugin->info['plugin_name']}&utm_content=top' target='_blank' 
                        style='background: transparent; width: auto; border: 0 none; box-shadow: none; padding: 0; margin: 0;'>
                            <img alt='{$plugin['title']}' src='https://berocket.ams3.cdn.digitaloceanspaces.com/g/bf21-1202x280.jpg' style='display: block;'>
                        </a>
                    </div>";
                } else if ( time() > 1637841600+302400 and time() < 1637841600+302400+518400 ) {
	                echo "
                    <div class='berocket-above-settings-banner berocket-cm21-settings-wrapper' style='background: #07002e; padding: 0;'>
                        <a href='{$plugin['url']}?utm_source=free_plugin&utm_medium=settings&utm_campaign={$cur_plugin->info['plugin_name']}&utm_content=top' target='_blank' >
                            <img alt='{$plugin['title']}' src='https://berocket.ams3.cdn.digitaloceanspaces.com/g/cm21.jpg'>
                            <div class='berocket-cm21-settings'>
                                <div class='berocket-cm21-settings-header'>
                                    <p>Don't lose another 5% of the discount. Purchase now!</p>
                                </div>
                                <p style='top: 30%; left: 6%; '><span>Monday: <span style='padding-left: 20px; font-size: 1.25em; font-weight: bold;'>-30%</span></span></p>
                                <p style='top: 32%; left: 55%;'><span>Tuesday: <span style='padding-left: 15px; font-size: 1.2em; font-weight: bold;'>-25%</span></span></p>
                                <p style='top: 48%; left: 10%;'><span>Wednesday: <span style='padding-left: 5px; font-size: 1.15em'>-20%</span></span></p>
                                <p style='top: 50%; left: 59%;'><span>Thursday: <span style='padding-left: 10px; font-size: 1.1em'>-15%</span></span></p>
                                <p style='top: 66%; left: 16%;'><span>Friday: <span style='padding-left: 20px; font-size: 0.9em'>-10%</span></span></p>
                                <p style='top: 68%; left: 63%;'><span>Saturday: <span style='padding-left: 15px; font-size: 0.9em'>-5%</span></span></p>
                            </div>
                            <div class='berocket-cm21-settings-mobiles-title' style='display: none;'>Up to 30% off sitewide!</div>
                        </a>
                    </div>";
                } else {
	                echo "
                    <div class='berocket-above-settings-banner' style='background: {$plugin['bg_top']};'>
                        <div style='background-image: url(\"{$plugin['image_top']}\")'>
                            <div>
                                <h1>{$plugin['title']}</h1>
                                <p>" . ( empty( $plugin['desc_top'] ) ? $plugin['desc'] : $plugin['desc_top'] ) . "</p>
                                <a href='{$plugin['url']}" . ( str_contains( $plugin[ 'url' ], '?') ? '&' : '?' ) . "utm_source=free_plugin&utm_medium=settings&utm_campaign={$cur_plugin->info['plugin_name']}&utm_content=top' target='_blank'>" . __( 'Get it now', 'BeRocket_domain' ) . "</a>
                            </div>
                        </div>
                    </div>
                    ";
                }
                echo "
                <style>
                    .berocket-above-settings-banner {
                        width: 100%;
                        color: white;
                        border: 0 none;
                        position: relative;
                        margin: 5px 0 15px;
                        display: block;
                        padding: 0;
                        background: #b54df8;
                        align-items: center;
                        text-align: center;
                        height: 350px;
                    }
                    .berocket-above-settings-banner > div {
                        background-repeat: no-repeat;
                        background-color: transparent;
                        background-size: contain;
                        height: 100%;
                        vertical-align: middle;
                        display: flex;
                        flex-wrap: wrap;
                        align-items: center;
                        justify-content: center;
                    }
                    .berocket-above-settings-banner > div {
                        background-position: center right;
                        padding-right: 250px;
                    }
                    .berocket-above-settings-banner h1 {
                        color: white;
                        padding-bottom: 25px;
                        font-size: 45px;
                        max-width: 550px;
                        margin: 0 auto;
                        font-weight: 700;
                        text-align: center;
                        line-height: 1.1em; 
                    }
                    .berocket-above-settings-banner p {
                        color: white;
                        font-weight: 400;
                        font-size: 18px;
                        max-width: 700px;
                        margin: 0 auto 30px;
                        text-align: center;
                    }
                    .berocket-above-settings-banner a {
                        background: linear-gradient(to right, #ff1305, #fed549) !important;
                        border-radius: 50px !important;
                        padding: 10px 25px 12px !important;
                        min-width: 200px !important;
                        border: 0 !important;
                        text-shadow: none;
                        text-decoration: none;
                        display: inline-block;
                        transition: all 0.2s;
                        position: relative;
                        line-height: 1.4em !important;
                        font-weight: 400 !important;
                        font-size: 22px !important;
                        color: white !important;
                        margin: 5px 5px 15px !important;
                        text-align: center;
                        box-shadow: 0px 4px 12px 0 #3333333b !important;
                        cursor: pointer;
                    }
                    .berocket-cm21-settings {
                        text-align: left;
                        position: relative;
                    }
                    .berocket-cm21-settings > p{
                        font-size: 1.1em;
                        position: absolute;
                    }
                    .berocket-cm21-settings > p > span {
                        position: relative;
                        z-index: 100;
                    }
                    .berocket-cm21-settings > p:after {
                        content: '';
                        position: absolute;
                        left: -10px;
                        bottom: -2px;
                        background: linear-gradient(106deg, #ff30b8, #ffe390 50%, #41ebfd);
                        height: 10px;
                        width: 50%;
                        transform: skewX(-20deg);
                    }
                    .berocket-cm21-settings > p:before {
                        content: '';
                        position: absolute;
                        left: -8px;
                        bottom: 0;
                        background: #07002e;
                        height: 10px;
                        width: 50%;
                        transform: skewX(-20deg);
                        z-index: 10; 
                    }
                    .berocket-cm21-settings-header {
                        margin-top: 20px;
                        background: linear-gradient(106deg, #ff30b8, #ffe390 50%, #41ebfd);
                        padding: 3px;
                    }
                    .berocket-cm21-settings-header p {
                        font-size: 1.2em;
                        background: #07002e;
                        margin: 0;
                        padding: 12px 18px;
                    }
                    .berocket-cm21-settings-wrapper > a {
                        background: linear-gradient(46deg, #07002e 50%, #f743f4);
                        width: auto;
                        border: 0 none;
                        box-shadow: none;
                        padding: 0;
                        margin: 0;
                        display: flex;
                    }
                    @media (max-width: 1500px) {
                        .berocket-above-settings-banner a img {
                            max-height: 200px;
                        }
                        .berocket-cm21-settings-wrapper > a {
                            background: linear-gradient(46deg, #07002e 75%, #f743f4);
                        }
                        .berocket-cm21-settings-header {
                            padding: 2px;
                        }
                        .berocket-cm21-settings-header p {
                            font-size: 1.1em;
                            padding: 9px 14px;
                        }
                    }
                    @media (max-width: 1400px) {
                        .berocket-above-settings-banner {
                            height: 250px;
                        }
                        .berocket-above-settings-banner h1 {
                            font-size: 30px;
                            max-width: 400px;
                            padding-bottom: 15px;
                        }
                        .berocket-above-settings-banner p {
                            font-size: 16px;
                            max-width: 600px;
                            margin: 0 auto 10px;
                        }
                        .berocket-above-settings-banner a {
                            margin: 5px!important;
                        }
                    }
                    @media (max-width: 1200px) {
                        .berocket-above-settings-banner a img {
                            max-height: 150px;
                            max-width: 100%;
                        }
                        .berocket-cm21-settings-header {
                            margin-top: 10px;
                            margin-left: -35px;
                        }
                        .berocket-cm21-settings-header p {
                            font-size: 1em;
                            padding: 6px 11px;
                        }
                        .berocket-cm21-settings > p {
                            font-size: 0.8em;
                        }
                    }
                    @media (max-width: 1200px) {
                        .berocket-cm21-settings-wrapper a img {
                            width: 240px;
                            object-fit: cover;
                            height: 150px;
                        }
                        .berocket-cm21-settings-header {
                            margin-left: -15px;
                        }
                        .berocket-cm21-settings > p {
                            margin-top: 10px;
                        }
                    }
                    @media (max-width: 728px) {
                        .berocket-above-settings-banner > div {
                            background-image: none !important;
                            padding-right: 0;
                        }
                        .berocket-cm21-settings-header {
                            display: block;
                            height: 1px;
                            overflow: hidden;
                            border: 0;
                            padding: 0;
                            background: transparent;
                        }
                        .berocket-cm21-settings-header p{
                            background: transparent;
                        }
                        .berocket-cm21-settings > p {
                            margin-top: -15px;
                            margin-bottom: 30px;
                            margin-left: -10px;
                        }
                        .berocket-cm21-settings > p:nth-child(2n+1) {
                            margin-left: -40px;
                        }
                    }
                    @media (max-width: 620px) {
                        .berocket-cm21-settings > p {
                            display: none;
                        }
                        .berocket-cm21-settings-wrapper > a {
                            display: block;
                            background: linear-gradient(180deg, #07002e 75%, #5b0b5a);
                        }
                        .berocket-cm21-settings-mobiles-title {
                            display: block !important;
                            padding: 10px 10px 30px;
                            font-size: 20px;
                        }
                    }
                    @media (max-width: 400px) {
                        .berocket-above-settings-banner h1 {
                            font-size: 22px;
                        }
                        .berocket-above-settings-banner a {
                            padding: 10px 20px !important;
                        }
                    }
                </style>
                ";
            }
        }

        function show_related_window( $html, $plugin_id, $plugin, $location = 'sidebar' ) {
            add_action( 'admin_footer', array( $this, 'wp_footer_js' ) );
            $plugins = self::get_plugin_data();
            $plugins_use = array_rand($plugins, 2);

            foreach($plugins_use as $plugin_use) {
                $plugin_data = $plugins[$plugin_use];
                $html .= '
                <div class="berocket_related_plugins berocket-related-plugins-page-' . $plugin_data[ 'id' ] . '">
                    <div style="background-color: ' . $plugin_data[ 'bg' ] . ';">
                        <img style="object-fit: cover;height: 100%;width: 100%;" src="' . $plugin_data[ 'image' ] . '" />
                    </div>
                    <div>
                        <div>
                            <h3>' . $plugin_data[ 'title' ] . '</h3>
                            <p>' . $plugin_data[ 'desc' ] . '</p>
                            <a class="brfirst" href="' . $plugin_data[ 'url' ]
                                . ( str_contains( $plugin_data[ 'url' ], '?') ? '&' : '?' )
                                . 'utm_source=free_plugin&utm_medium=settings&utm_campaign=' . $plugin->info['plugin_name']
                                . '&utm_content=sidebar" target="_blank">From: $' . $plugin_data[ 'price' ] . '</a>
                        </div>
                    </div>
                </div>';
            }

            $html .= '
            <style>
            .berocket_related_plugins {
                border-radius: 3px;
                box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.06);
                overflow: auto;
                position: relative;
                background-color: white;
                color: rgba(0, 0, 0, 0.87);
                padding: 0;
                margin-bottom: 30px;
                box-sizing: border-box;
                text-align: center;
                float: right;
                clear: right;
                width: 28%;
                display: flex;
                align-items: stretch;
            }
            .berocket_related_plugins > div {
                box-sizing: border-box;
                display: flex;
                align-items: center;
                float: left;
                width: 45%;
            }
            .berocket_related_plugins > div:last-child {
                width: 55%;
                padding: 4px 10px;
            }
            .berocket_related_plugins > div h3 {
                margin-top: 0;
            }
            .berocket_related_plugins a {
                margin-top: 30px;
                margin-bottom: 20px;
                box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
                text-shadow: none;
                min-width: 120px;
                width: 70%;
                -moz-user-select: none;
                background-color: #a46497;
                background: linear-gradient(to right, #db16fc, #1d63c5) !important;
                box-sizing: border-box;
                cursor: pointer;
                display: inline-block;
                font-size: 17px;
                font-weight: 500;
                outline: 0 none;
                padding: 6px 10px;
                position: relative;
                text-align: center;
                text-decoration: none;
                transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s, background-color 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s;
                white-space: nowrap;
                height: auto;
                vertical-align: top;
                line-height: 25px;
                border-radius: 3px;
                font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
                margin: 5px 0;
                color: white;
            }
            .berocket_related_plugins a:hover {
                background: #ff6e68 none repeat scroll 0 0;
            }
            .berocket_related_plugins.berocket-related-plugins-page-1 > div:first-child {
                align-items: start;
            }
            @media screen and (min-width: 901px) and (max-width: 1700px), screen and (max-width: 500px) {
                .berocket_related_plugins > div h3 {
                    margin-bottom: 0;
                    font-size: 14px;
                }
                .berocket_related_plugins > div p {
                    margin-top: 5px;
                    margin-bottom: 5px;
                    font-size: 13px;
                    line-height: 1.3;
                }
                .berocket_related_plugins > div a {
                    padding: 1px 5px;
                    min-width: 100px;
                    width: 60%;
                }
            }
            @media screen and (max-width: 1400px) {
                .berocket_related_plugins > div {
                    width: 30%;
                }
                .berocket_related_plugins > div:last-child {
                    width: 70%;
                }
            }
            @media screen and (min-width: 901px) and (max-width: 1200px) {
                .berocket_related_plugins{
                    display: block;
                }
                .berocket_related_plugins > div{
                    float: none;
                    clear: both;
                    width: 100%;
                }
                .berocket_related_plugins > div:first-child {
                    height: 20px;
                    box-shadow: 0 0 4px 0px #ccc;
                    margin-bottom: 6px;
                }
                .berocket_related_plugins > div:last-child{
                    width: 100%;
                }
                .berocket_related_plugins > div:first-child img {
                    display: none;
                }
            }
            @media screen and (max-width: 900px) {
                .berocket_related_plugins {
                    float: none;
                    width: 100%;
                    margin-top: 30px;
                    margin-bottom: 0;
                }
            }
            </style>';

            return $html;
        }

        function show_feature_request_window($html, $plugin_id) {
            $disabled = get_option('berocket_admin_notices_rate_stars');
            $plugins = apply_filters('berocket_admin_notices_rate_stars_plugins', array());
            foreach($plugins as $plugin) {
                if( $plugin['id'] == $plugin_id ) {
                    add_action('admin_footer', array($this, 'wp_footer_js'));
                    $meta_data = '?utm_source=free_plugin&utm_medium=plugins&utm_campaign='.$plugin['plugin_name'];
                    $html .= '
                    <div class="berocket_feature_request berocket-feature-request berocket-feature-request-'.$plugin['id'].'">
                        <a class="berocket_feature_request_button" href="#feature_request">
                            <picture>
                                <source type="image/webp" srcset="'.plugin_dir_url( __FILE__ ).'../assets/images/Feature-request.webp" alt="Feature Request">
                                <img src="https://berocket.com/images/plugin/Feature-request.png" style="width: 100%;" alt="Feature Request">
                            </picture>
                        </a>
                        <div class="berocket_feature_request_form" style="display: none;">
                            <picture>
                                <source type="image/webp" srcset="'.plugin_dir_url( __FILE__ ).'../assets/images/Feature-request-form-title.webp" alt="Feature Request">
                                <img src="https://berocket.com/images/plugin/Feature-request-form-title.png" style="width: 100%;" alt="Feature Request">
                            </picture>
                            <form class="berocket_feature_request_inside">
                                <input name="brfeature_plugin" type="hidden" value="'.$plugin['id'].'">
                                <input name="brfeature_title" placeholder="'.__('Feature Title', 'BeRocket_domain').'">
                                <input name="brfeature_email" placeholder="'.__('Email (optional)', 'BeRocket_domain').'">
                                <textarea name="brfeature_description" placeholder="'.__('Feature Description', 'BeRocket_domain').'"></textarea>
                                <button class="berocket_feature_request_submit" type="submit">'.__('SEND FEATURE REQUEST', 'BeRocket_domain').'</button>
                            </form>
                            <div style="margin-bottom: 10px;">* <small>This form will be sended to <a target="_blank" href="https://berocket.com' . $meta_data . '">berocket.com</a></small></div>
                        </div>
                        <div class="berocket_feature_request_thanks" style="display: none;">
                            <picture>
                                <source type="image/webp" srcset="'.plugin_dir_url( __FILE__ ).'../assets/images/Thank-you.webp" alt="Feature Request">
                                <img src="https://berocket.com/images/plugin/Thank-you.png" style="width: 100%;" alt="Feature Request">
                            </picture>';
                    if( empty($disabled[$plugin_id]) || $disabled[$plugin_id]['time'] != 0 ) {
                        $html .= '
                        <div class="berocket_feature_request_rate berocket-rate-stars-plugin-feature-'.$plugin_id.'">
                            <h3>'.__("While you're here, you could rate this plugin", 'BeRocket_domain').'</h3>
                            <ul class="berocket-rate-stars-block">
                            <li><a class="berocket_rate_close brfirst"
                                data-plugin="'.$plugin['id'].'"
                                data-action="berocket_rate_stars_close"
                                data-prevent="0"
                                data-later="0"
                                data-function="berocket_rate_star_close_notice"
                                data-thanks_html=\'<picture><source type="image/webp" srcset="'.plugin_dir_url( __FILE__ ).'../assets/images/Thank-you.webp" alt="Feature Request"><img src="https://berocket.com/images/plugin/Thank-you.png" style="width: 100%;" alt="Feature Request"></picture><h3 class="berocket_thank_you_rate_us">'.__('Each good feedback is very important for plugin growth', 'BeRocket_domain').'</h3>\'
                                href="https://wordpress.org/support/plugin/'.$plugin['free_slug'].'/reviews/?filter=5#new-post"
                                target="_blank">'.__('This plugin deserves 5 stars', 'BeRocket_domain').'</a></li>
                            <li><a class="berocket_rate_next_time brsecond"
                                href="#later">'.__("I'll rate it next time", 'BeRocket_domain').'</a></li>
                            <li><a class="berocket_rate_close brthird"
                                data-plugin="'.$plugin['id'].'"
                                data-action="berocket_rate_stars_close"
                                data-prevent="1"
                                data-later="0"
                                data-function="berocket_rate_star_close_notice"
                                href="#close">'.__('I already rated it', 'BeRocket_domain').'</a></li>
                            </ul>
                        </div>';
                    }
                        $html .= '</div>
                    </div>
                    <style>
                        .berocket_feature_request_inside input,
                        .berocket_feature_request_inside textarea,
                        .berocket_feature_request_submit {
                            width: 90%;
                        }
                        .berocket_feature_request_submit {
                            margin-top: 30px;
                            margin-bottom: 20px;
                            color: #fff;
                            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.26);
                            text-shadow: none;
                            border: 0 none;
                            min-width: 120px;
                            -moz-user-select: none;
                            background: #ff5252 none repeat scroll 0 0;
                            box-sizing: border-box;
                            cursor: pointer;
                            display: inline-block;
                            font-size: 14px;
                            outline: 0 none;
                            padding: 8px;
                            position: relative;
                            text-align: center;
                            text-decoration: none;
                            transition: box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s, background-color 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) 0s;
                            white-space: nowrap;
                            height: auto;
                            vertical-align: top;
                            line-height: 25px;
                            border-radius: 3px;
                            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
                            font-weight: bold;
                            margin: 5px 0 15px;
                            padding: 10px;
                        }
                        .berocket_feature_request_submit:hover,
                        .berocket_feature_request_submit:focus,
                        .berocket_feature_request_submit:active {
                            background: #ff6e68 none repeat scroll 0 0;
                            color: white;
                        }
                        .berocket_feature_request_button {
                            line-height: 0;
                            overflow: hidden;
                            display: inline-block;
                        }
                        .berocket_feature_request_form {
                            overflow: auto;
                        }
                        .berocket_feature_request_button,
                        .berocket_feature_request_form,
                        .berocket_feature_request_thanks {
                            border-radius: 3px;
                            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.06);
                            position: relative;
                            background-color: white;
                            color: rgba(0, 0, 0, 0.87);
                            margin-bottom: 30px;
                            box-sizing: border-box;
                            text-align: center;
                            float: right;
                            clear: right;
                            width: 28%;
                        }
                        .berocket_feature_request_inside {
                            padding: 0 25px;
                        }
                        .berocket_feature_request_button img,
                        .berocket_feature_request_form img {
                            width: 100%;
                        }
                        .berocket_feature_request_inside input,
                        .berocket_feature_request_inside textarea {
                            outline: none;
                            box-shadow: none;
                            resize: none;
                            margin-bottom: 10px;
                            margin-top: 10px;
                            box-shadow: 0px 0px 15px #aaa;
                            border-radius: 3px;
                            padding: 10px;
                            border: 2px solid #FFFFFF;
                        }
                        .berocket_feature_request_inside textarea {
                            height: 150px;
                            overflow: auto;
                        }
                        @media screen and (min-width: 901px) and (max-width: 1200px) {
                            .berocket_feature_request_inside{
                                padding-left: 10px;
                                padding-right: 10px;
                            }
                        }
                        .berocket_feature_request_thanks .berocket_feature_request_rate ul {
                            margin-left: 20%;
                            list-style: disc;
                        }
                        @media screen and (max-width: 900px) {
                            .berocket_feature_request_thanks .berocket_feature_request_rate ul {
                                margin-left: -80px;
                                padding-left: 50%;
                            }
                            .berocket_feature_request {
                                margin-top: 30px;
                            }
                            .berocket_feature_request_button,
                            .berocket_feature_request_form,
                            .berocket_feature_request_thanks {
                                float: none;
                                width: 100%;
                                margin-bottom: 0;
                            }
                            .berocket_feature_request_inside input,
                            .berocket_feature_request_inside textarea,
                            .berocket_feature_request_submit{
                                float: none;
                                width: 100%;
                            }
                        }
                        .berocket_feature_request_inside input.brfeature_error,
                        .berocket_feature_request_inside textarea.brfeature_error {
                            box-shadow: 0px 0px 15px #f00;
                            border-color: #ff0000;
                            animation-name: brfeature_error;
                            animation-duration: 2s;
                        }
                        @keyframes brfeature_error {
                            0%   {border-color: #ffffff;}
                            10%  {border-color: #ff0000;}
                            20%  {border-color: #ff9999;}
                            30% {border-color: #ff0000;}
                            40%   {border-color: #ff9999;}
                            50%  {border-color: #ff0000;}
                            60%  {border-color: #ff9999;}
                            70% {border-color: #ff0000;}
                            80%   {border-color: #ff9999;}
                            100%  {border-color: #ff0000;}
                        }
                        .berocket_feature_request_thanks {
                            padding-top: 20px;
                            padding-bottom: 20px;
                        }
                        .berocket_feature_request_thanks .berocket_feature_request_rate h3 {
                            color: #555;
                        }
                        .berocket_feature_request_thanks .berocket_feature_request_rate ul li {
                            text-align: left;
                        }
                    </style>';
                    return $html;
                }
            }
        }
        function wp_footer_js() {
            ?>
            <script>
                jQuery(document).on('click', '.berocket-rate-stars-block .berocket_rate_close', function(event) {
                    var $this = jQuery(this);
                    if( $this.data('prevent') ) {
                        event.preventDefault();
                    }
                    var data = $this.data();
                    if( $this.data('function') ) {
                        if( typeof(window[$this.data('function')]) == 'function' ) {
                            window[$this.data('function')](data);
                        }
                    }
                    jQuery.post(ajaxurl, data, function(result) {
                        if( $this.data('function_after') ) {
                            if( typeof(window[$this.data('function_after')]) == 'function' ) {
                                window[$this.data('function_after')](result, data);
                            }
                        }
                    });
                });
                function berocket_rate_star_close_notice(button_data) {
                    jQuery('.berocket-rate-stars-'+button_data.plugin).slideUp('100');
                    console.log(button_data);
                    if( ! button_data.prevent ) {
                        jQuery('.berocket-rate-stars-plugin-page-'+button_data.plugin).html(button_data.thanks_html);
                        jQuery('.berocket-rate-stars-plugin-feature-'+button_data.plugin).slideUp(100);
                    }
                    if( button_data.prevent && ! button_data.later ) {
                        jQuery('.berocket-rate-stars-plugin-page-'+button_data.plugin).slideUp(100);
                        jQuery('.berocket-rate-stars-plugin-feature-'+button_data.plugin).slideUp(100);
                    }
                }
                jQuery(document).on('click', '.berocket_feature_request_button', function(event) {
                    event.preventDefault();
                    var $this = jQuery(this);
                    $this.hide();
                    $this.parents('.berocket_feature_request').find('.berocket_feature_request_form').show();
                });
                jQuery(document).on('submit', '.berocket_feature_request_inside', function(event) {
                    event.preventDefault();
                    var form_data = jQuery(this).serialize();
                    var send = true;
                    if( ! jQuery(this).find('[name=brfeature_title]').val() ) {
                        send = false;
                        jQuery(this).find('[name=brfeature_title]').addClass('brfeature_error');
                    }
                    if( ! jQuery(this).find('[name=brfeature_description]').val() ) {
                        send = false;
                        jQuery(this).find('[name=brfeature_description]').addClass('brfeature_error');
                    }
                    if( send ) {
                        form_data = form_data+'&action=berocket_feature_request_send';
                        jQuery.post(ajaxurl, form_data);
                        jQuery(this).parents('.berocket_feature_request_form').hide().parents('.berocket_feature_request').find('.berocket_feature_request_thanks').show();
                    }
                });
                jQuery(document).on('change', '.brfeature_error', function() {
                    jQuery(this).removeClass('brfeature_error');
                });
                jQuery(document).on('click', '.berocket_feature_request_rate .berocket_rate_close', function(event) {
                    jQuery(this).parents('.berocket_feature_request_rate').slideUp(100);
                });
                jQuery(document).on('click', '.berocket_rate_next_time', function(event) {
                    event.preventDefault();
                    jQuery(this).parents('.berocket_feature_request_rate').slideUp(100);
                });
            </script>
            <?php
        }
    }
    new berocket_admin_notices_rate_stars;
}
?>
