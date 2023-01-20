<?php

namespace sysbasics;

if(!is_admin())
	return;

global $pagenow;

if($pagenow != "plugins.php")
	return;

if(defined('sysbasics_DEACTIVATE_FEEDBACK_FORM_INCLUDED'))
	return;
define('sysbasics_DEACTIVATE_FEEDBACK_FORM_INCLUDED', true);

add_action('admin_enqueue_scripts', function() {
	
	// Enqueue scripts
	wp_enqueue_script('remodal', plugin_dir_url(__FILE__) . 'remodal.min.js');
	wp_enqueue_style('remodal', plugin_dir_url(__FILE__) . 'remodal.css');
	wp_enqueue_style('remodal-default-theme', plugin_dir_url(__FILE__) . 'remodal-default-theme.css');
	
	wp_enqueue_script('sysbasics-deactivate-feedback-form', plugin_dir_url(__FILE__) . 'deactivate-feedback-form.js');
	wp_enqueue_style('sysbasics-deactivate-feedback-form', plugin_dir_url(__FILE__) . 'deactivate-feedback-form.css');
	
	// Localized strings
	wp_localize_script('sysbasics-deactivate-feedback-form', 'sysbasics_deactivate_feedback_form_strings', array(
		'quick_feedback'			=> __('Quick Feedback', 'sysbasics'),
		'foreword'					=> __('If you would be kind enough, please tell us why you\'re deactivating?', 'sysbasics'),
		'better_plugins_name'		=> __('Please tell us which plugin?', 'sysbasics'),
		'missing_feature'		    => __('Which feature you are missing ?', 'sysbasics'),
		'please_tell_us'			=> __('Please tell us the reason so we can improve the plugin', 'sysbasics'),
		'do_not_attach_email'		=> __('Do not send my e-mail address with this feedback', 'sysbasics'),
		
		'brief_description'			=> __('Please give us any feedback that could help us improve', 'sysbasics'),
		
		'cancel'					=> __('Cancel', 'sysbasics'),
		'skip_and_deactivate'		=> __('Skip &amp; Deactivate', 'sysbasics'),
		'submit_and_deactivate'		=> __('Submit &amp; Deactivate', 'sysbasics'),
		'please_wait'				=> __('Please wait', 'sysbasics'),
		'thank_you'					=> __('Thank you!', 'sysbasics')
	));
	
	// Plugins
	$plugins = apply_filters('sysbasics_deactivate_feedback_form_plugins', array());
	
	// Reasons
	$defaultReasons = array(
		'suddenly-stopped-working'	=> __('The plugin suddenly stopped working', 'sysbasics'),
		'plugin-broke-site'			=> __('The plugin broke my site', 'sysbasics'),
		'no-longer-needed'			=> __('I don\'t need this plugin any more', 'sysbasics'),
		
		'temporary-deactivation'	=> __('It\'s a temporary deactivation, I\'m troubleshooting', 'sysbasics'),
		'other'						=> __('Other', 'sysbasics'),
		'found-better-plugin'		=> __('I found a better plugin', 'sysbasics'),
		'missing-feature'           => __('Missing a feature', 'sysbasics')
	);
	
	foreach($plugins as $plugin)
	{
		$plugin->reasons = apply_filters('sysbasics_deactivate_feedback_form_reasons', $defaultReasons, $plugin);
	}
	
	// Send plugin data
	wp_localize_script('sysbasics-deactivate-feedback-form', 'sysbasics_deactivate_feedback_form_plugins', $plugins);
});

/**
 * Hook for adding plugins, pass an array of objects in the following format:
 *  'slug'		=> 'plugin-slug'
 *  'version'	=> 'plugin-version'
 * @return array The plugins in the format described above
 */
add_filter('sysbasics_deactivate_feedback_form_plugins', function($plugins) {
	return $plugins;
});

