<?php
if( ! class_exists('BeRocket_AAPF_compat_Beaver_builder') ) {
    class BeRocket_AAPF_compat_Beaver_builder {
        public $attributes;
        function __construct() {
            add_filter('fl_builder_register_module_settings_form', array($this, 'edit_form'), 10, 2);
            add_action('fl_builder_before_render_module', array($this, 'content_by_id_attrs'), 10, 2);
        }
        function edit_form($form, $slug) {
            if( $slug == 'woocommerce' && ! empty($form['general']['sections']['multiple_products']) ) {
                $form['general']['sections']['multiple_products']['fields'] = berocket_insert_to_array(
                    $form['general']['sections']['multiple_products']['fields'],
                    'category_slug',
                    array('bapf_apply'         => array(
						'type'    => 'select',
						'label'   => __( 'Apply BeRocket AJAX Filters', 'BeRocket_AJAX_domain' ),
						'default' => 'default',
						'options' => array(
							'default' => __( 'Default', 'BeRocket_AJAX_domain' ),
                            'enable'  => __( 'Enable', 'BeRocket_AJAX_domain' ),
                            'disable' => __( 'Disable', 'BeRocket_AJAX_domain' ),
						),
					))
                );
            }
            return $form;
        }
        function content_by_id_attrs($module) {
            if( $module->slug == 'woocommerce' && $module->settings->layout == 'products' ) {
                $attributes = (array)$module->settings;
                if( ! empty($attributes['bapf_apply']) && $attributes['bapf_apply'] == 'enable' ) {
                    echo '[brapf_next_shortcode_apply apply=1]';
                } elseif( ! empty($attributes['bapf_apply']) && $attributes['bapf_apply'] == 'disable' ) {
                    echo '[brapf_next_shortcode_apply apply=0]';
                }
            }
        }
    }
    new BeRocket_AAPF_compat_Beaver_builder();
}