<?php
class BeRocket_aapf_separate_link_addon extends BeRocket_framework_addon_lib {
    public $addon_file = __FILE__;
    public $plugin_name = 'ajax_filters';
    public $php_file_name   = 'separate_vars';
    function get_addon_data() {
        $data = parent::get_addon_data();
        return array_merge($data, array(
            'addon_name'    => __('Link like WooCommerce (BETA)', 'BeRocket_AJAX_domain'),
            'tooltip'       => __('Links after filtering will look like Woocommerce.<br>
            <i>Example:</i><br><span style="color: #aaf;">https://example.com/shop/?pa-color=bronze,green&pa-product_cat_operator=or&pa-product_cat=dress</span>
            <a class="button" href="https://docs.berocket.com/docs_section/link-like-woocommerce-beta" target="_blank">Read more</a>', 'BeRocket_AJAX_domain')
        ));
    }
}
new BeRocket_aapf_separate_link_addon();
