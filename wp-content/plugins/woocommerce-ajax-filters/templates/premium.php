<?php
$feature_list = ( empty($this->cc->feature_list) ? null : $this->cc->feature_list );
$dplugin_link = 'https://berocket.com/' . $this->cc->values['premium_slug'];
$dplugin_lic = br_get_value_from_array($this->cc->info, 'lic_id');
$plugin_landing_page = 'https://berocket.com/woocommerce-ajax-products-filter/?coupon=pfum3vap&utm_source=free_plugin&utm_medium=settings&utm_campaign=ajax_filters&utm_content=read_more';
$dplugin_lic_link = $plugin_landing_pricing = 'https://berocket.com/woocommerce-ajax-products-filter/?coupon=pfum3vap&utm_source=free_plugin&utm_medium=settings&utm_campaign=ajax_filters&utm_content=buy_now#buy_now';
if ( isset($this->plugin_version_capability) && $this->plugin_version_capability <= 5 ) {
    echo apply_filters('berocket_rate_plugin_window', '', br_get_value_from_array($this->cc->info, 'id'));
    if ( ! empty( $feature_list ) && count( $feature_list ) > 0 ) { ?>
        <div class="paid_features">
            <?php
            $feature_text = '';
            foreach ( $feature_list as $feature ) {
                $feature_text .= '<li>' . $feature . '</li>';
            }
            $text = '<h3>Unlock Premium features!</h3>
            <div>
            <ul>
                %feature_list%
            </ul>
            </div>
            <div class="premium_buttons">
                <span>Read more about</span>
                <a class="get_premium_version" href="%link%" target="_blank">PREMIUM VERSION</a>
                <span class="divider">OR</span>
                <a class="buy_premium_version" href="%licence_link%" target="_blank">BUY NOW</a>
                <span>and get Up to <b>%discount% discount</b></span>
            </div>
            <p class="berocket_paid_features_support">Support the plugin by purchasing paid version<br>
            This will help us release next version</p>';

            $dpdiscount = '60%';
            if ( isset( $start_time ) and isset( $end_time ) and isset( $discount ) and time() > $start_time && time() < $end_time and (int) $discount > 15 ) {
                $dpdiscount = $discount;
            }

            $text = str_replace( '%feature_list%', $feature_text,           $text );
            $text = str_replace( '%link%',         $plugin_landing_page,    $text );
            $text = str_replace( '%licence%',      $plugin_landing_page,    $text );
            $text = str_replace( '%licence_link%', $plugin_landing_pricing, $text );
            $text = str_replace( '%discount%',     $dpdiscount,             $text );
            $text = str_replace( '%plugin_name%',  (empty($plugin_info['Name']) ? '' : $plugin_info['Name']),      $text );
            $text = str_replace( '%plugin_link%',  (empty($plugin_info['PluginURI']) ? '' : $plugin_info['PluginURI']), $text );
            echo $text;
            ?>
        </div>
        <?php
    }
    echo apply_filters('berocket_related_plugins_window', '', br_get_value_from_array($this->cc->info, 'id'), $this);
    echo apply_filters('berocket_feature_request_window', '', br_get_value_from_array($this->cc->info, 'id'), $this);
    $subscribed = get_option('berocket_email_subscribed');
    if( ! $subscribed ) {
        $user_email = wp_get_current_user();
        if( isset($user_email->user_email) ) {
            $user_email = $user_email->user_email;
        } else {
            $user_email = '';
        }
        ?>
        <div class="berocket_subscribe berocket_subscribe_form" method="POST" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
            <h3>OUR NEWSLETTER</h3>
            <p>Get awesome content delivered straight to your inbox.</p>
            <input type="hidden" name="berocket_action" value="berocket_subscribe_email">
            <input class="berocket_subscribe_email" type="email" name="email" placeholder="Enter your email address" value="<?php echo $user_email; ?>">
            <p class="error" style="display: none;">Incorrect EMail. Please check it and try again</p>
            <input type="submit" class="button-primary button berocket_notice_submit" value="SUBSCRIBE">
        </div>
        <?php
        berocket_admin_notices::echo_jquery_functions();
    }
}
