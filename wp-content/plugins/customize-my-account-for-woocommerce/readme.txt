=== Customize My Account for WooCommerce ===
Contributors: phppoet
Tags: woocommerce,customize,myaccount,account,endpoints,pages,add,edit,links
Requires at least: 4.0
Tested up to: 6.1.1
WC Tested up to: 7.2.0
WC Requires at least: 4.0
Requires PHP: 5.2
Stable tag: 1.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Customize your woocommerce my account page. Modify existing endpoints.



== Description ==

<h3>Customize My Account for WooCommerce</h3>

Customize your default my account page. Reorder them , hide existing core endpoints. You will also be able to change the default endpoint. 



<p>Check our <a href="https://codecanyon.net/user/sysbasics/portfolio" title="WooCommerce Premium Plugins" rel="nofollow">Premium Plugins</a></p>

<p>Missing a feature or Want to inform us about bug ? <a href="https://bitbucket.org/woomatrix/woomatrix-main/issues/new" rel="nofollow">Contact us here</a></p>

== Customize My Account for WooCommerce features ==
- Modify existing endpoints.
- Add custom links to your my account pages.
- Drag and drop UI.

== Customize My Account for WooCommerce free version features ==
- Show/hide woocommerce core endpoints
- Reorder core woocommerce my account endpoints
- Add extra class to core endpoint
- Add New link as endpoint on my account page
- Show user avatar on my account page
- Drag and drop UI

== Modify woocommerce login and register form ==

- Add Custom Content in login and register page Including shortcodes
- Add Custom Content or Shortcode Before WooCommerce Login and Register Form.
- Add Custom Content or Shortcode Before WooCommerce Login Form.
- Add Custom Content or Shortcode Before WooCommerce Remember me checkbox.
- Add Custom Content or Shortcode After WooCommerce Login Form.
- Add Custom Content or Shortcode Before WooCommerce Registration Form.
- Add Custom Content or Shortcode Before WooCommerce Register Button.
- Add Custom Content or Shortcode After WooCommerce Registration Form.
- Add Custom Content or Shortcode After WooCommerce Login and Register Form.

== Customize My Account for WooCommerce pro version features ==
- All features of free version
- Support for link/endpoint/group endpoints
- Drage link/endpoints(core/new) into group and reorder them
- Show custom content on endpoints
- Set groups as show by default which will make group menu open on page load

<p><a href="https://1.envato.market/c/1314780/275988/4415?u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Fwoocommerce-customize-my-account-pro%2F31059126" title="Customize My Account for WooCommerce pro" rel="nofollow">Upgrade to pro</a></p>

<p>Check <a href="https://sysbasics.com/customize-my-account/wp-admin/" title="Customize My Account for WooCommerce pro" rel="nofollow">Live Demo</a> for Pro version features</p>
== Upgrade Notice ==


= 1.0.3 - 09 April 2021 


Version 1.0.3 - added hook to override default endpoint url 
              - fixed issue with accordion tab not loading on backend.
* Initial release

== Screenshots ==

1. 
2. 
3. 
4. 
5.
6.


== Frequently Asked Questions ==

= How to override endpoint url? =

Plugin has inbuilt hook which you can use to override my account endpoint url. Only use this if your setup is somehow not returning correct endpoint url. 

<pre>
add_filter('wcmamtx_override_endpoint_url','wcmamtx_override_endpoint_url',10,2);

function wcmamtx_override_endpoint_url($core_url,$key) {
	
	$new_url = ''.site_url().'/my-account/'.$key.'/';
	
	if ($key== "customer-logout") {
		$new_url = wp_nonce_url($new_url);
	}
	return $new_url;
}
</pre>

You may use <a href="https://wordpress.org/plugins/code-snippets/">Code Snippets</a> plugin to inject any extra php code. 

= Is plugin compatible with WPML ? =

yes. you can use this plugin with WPML and locotranslate both.

For WPML visit WPML/Theme and plugins localization menu and search for this plugin and click on “scan the selected plugins for scan” button.

Now visit WPML/string translation and click on “Translate texts in admin screens” link at the bottom.

There search for wcmamtx_advanced_settings and wcmamtx_plugin_options and check the fields you want to translate. Then apply the changes.

Now visit WPML/String translation and translate your strings there.

== Installation ==

Use automatic installer.
