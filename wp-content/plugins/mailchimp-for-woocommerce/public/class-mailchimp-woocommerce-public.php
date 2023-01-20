<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://mailchimp.com
 * @since      1.0.1
 *
 * @package    MailChimp_WooCommerce
 * @subpackage MailChimp_WooCommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MailChimp_WooCommerce
 * @subpackage MailChimp_WooCommerce/public
 * @author     Ryan Hungate <ryan@vextras.com>
 */
class MailChimp_WooCommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mailchimp-woocommerce-public.min.js', array(), $this->version.'.01');
		wp_localize_script($this->plugin_name, 'mailchimp_public_data', array(
			'site_url' => site_url(),
			'ajax_url' => admin_url('admin-ajax.php'),
			'disable_carts' => mailchimp_carts_disabled(),
			'subscribers_only' => mailchimp_carts_subscribers_only(),
			'language' => substr( get_locale(), 0, 2 ),
            'allowed_to_set_cookies' => mailchimp_allowed_to_use_cookie('mailchimp_user_email'),
		));

        // Enqueued script with localized data.
        wp_enqueue_script($this->plugin_name, '', array(), $this->version, true);

        // if we have the "fragment" we can just inject this vs. loading the file
        // otherwise, if we have the connected_site script url saved, we need to inject it and load from the CDN.
        //if (($site = mailchimp_get_connected_site_script_url()) && !empty($site)) {
        //   wp_enqueue_script($this->plugin_name.'_connected_site', $site, array(), $this->version, true);
        //}
	}

    /**
     * Add the inline footer script if the filter allows it.
     */
    public function add_inline_footer_script()
    {
        if (apply_filters( 'mailchimp_add_inline_footer_script', true)) {
            if (($fragment = mailchimp_get_connected_site_script_fragment()) && !empty($fragment)) {
                echo $fragment;
            }
        }
	}

	/**
	 * Add GDPR script to the checkout page
	 */
	public function add_JS_checkout()
	{
		wp_enqueue_script($this->plugin_name. '_gdpr', plugin_dir_url( __FILE__ ) .'js/mailchimp-woocommerce-checkout-gdpr.min.js', array(), $this->version, true);
	}
	
	public function user_my_account_opt_in()
    {
        $gdpr_fields = $this->user_my_account_gdpr_fields();
        include_once('partials/mailchimp-woocommerce-my-account.php');
    }

    public function user_my_account_opt_in_save($user_id)
    {
        $subscribed = isset($_POST['mailchimp_woocommerce_is_subscribed_checkbox']) &&
            ( $_POST['mailchimp_woocommerce_is_subscribed_checkbox'] == 'on' || $_POST['mailchimp_woocommerce_is_subscribed_checkbox'] == '1');
        update_user_meta( $user_id, 'mailchimp_woocommerce_is_subscribed', $subscribed);
    }

    /**
     * @return string
     */
    public function user_my_account_gdpr_fields()
    {
        return static::gdpr_fields();
    }

    /**
     * @param null $user
     * @return string
     */
    public static function gdpr_fields($user = null)
    {
        if (!mailchimp_is_configured()) {
            return "";
        }

        $api = mailchimp_get_api();
        $GDPRfields = $api->getCachedGDPRFields(mailchimp_get_list_id());

        $checkbox = '';

        if (!empty($GDPRfields) && is_array($GDPRfields)) {
            $checkbox .= "<div id='mailchimp-gdpr-fields'><p>";
            $checkbox .= __('Please select all the ways you would like to hear from us', 'mailchimp-for-woocommerce');
            $checkbox .= "<div class='clear'></div>";

            // if the user is logged in, we will pull the 'is_subscribed' property out of the meta for the value.
            // otherwise we use the default settings.
            //$saved_fields = get_user_meta(get_current_user_id(), 'mailchimp_woocommerce_gdpr_fields');

            /// if the user is logged in - and is already subscribed - just ignore this checkbox.
            $user = $user ? $user : wp_get_current_user();
            $current_gdpr_fields = array();
            if ($user && $cached_gdpr_fields = mailchimp_get_transient("mailchimp_woocommerce_gdpr_fields_{$user->ID}")) {
                foreach ($cached_gdpr_fields['value'] as $permission_id => $permission_value) {
                    $current_gdpr_fields[] = array(
                        'marketing_permission_id' => $permission_id,
                        'enabled' => $permission_value,
                    );
                }
            }
            if (empty($cached_gdpr_fields) && !empty($user) && $user->user_email) {
                try {
                    $member = mailchimp_get_api()->member(mailchimp_get_list_id(), $user->user_email);
                    $current_gdpr_fields = isset($member['marketing_permissions']) ?
                        $member['marketing_permissions'] : array();
                } catch (Exception $e) {
                    //mailchimp_error("GDPR ERROR", $e->getMessage());
                }
            }

            foreach ($GDPRfields as $key => $field) {
                $marketing_permission_id = $field['marketing_permission_id'];
                $text = $field['text'];
                $status = false;

                foreach ($current_gdpr_fields as $current_gdpr_field) {
                    if ($marketing_permission_id === $current_gdpr_field['marketing_permission_id']) {
                        $status = $current_gdpr_field['enabled'];
                        break;
                    }
                }

                // Add to the checkbox output
                $checkbox .= "<input class='mailchimp_woocommerce_gdpr_option' type='hidden' value='0' name='mailchimp_woocommerce_gdpr[{$marketing_permission_id}]'>";
                $checkbox .= "<input id='mailchimp_woocommerce_gdpr[{$marketing_permission_id}]' type='checkbox' name='mailchimp_woocommerce_gdpr[{$marketing_permission_id}]' value='1'".($status ? ' checked="checked"' : '').">";
                $checkbox .= "<label class='mailchimp_woocommerce_gdpr_label' for='mailchimp_woocommerce_gdpr[{$marketing_permission_id}]' ><span>{$text}</span></label>";
                $checkbox .= "<div class='clear'></div>";
            }
            $checkbox .= "</p></div>";
        }

        return $checkbox;
    }
}
