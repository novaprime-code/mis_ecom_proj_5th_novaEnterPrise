<?php
echo '<p><a class="berocket_aapf_filter_setup_wizard button" href="#support">', __( 'SETUP WIZARD', 'BeRocket_AJAX_domain' ), '</a></p>';
echo '<a href="https://docs.berocket.com/plugin/woocommerce-ajax-products-filter#post_product_filters" target="_blank">' . __('Get more information on BeRocket Documentation', 'BeRocket_AJAX_domain') . '</a>';
echo '<h3>', __( 'How to hide filter on page load?', 'BeRocket_AJAX_domain' ), '</h3>';
echo '<p>', sprintf(__( 'Enable in <strong>%s</strong> step option <strong>%s</strong> and <strong>%s</strong>', 'BeRocket_AJAX_domain' ), __('Additional', 'BeRocket_AJAX_domain'), __('Enable minimization option', 'BeRocket_AJAX_domain'), __('Minimize this widget on load?', 'BeRocket_AJAX_domain')), '</p>';
echo '<h3>', __( 'How to add reset filters button?', 'BeRocket_AJAX_domain' ), '</h3>';
echo '<p>', sprintf(__( 'Select <strong>%s</strong> in step <strong>%s</strong>', 'BeRocket_AJAX_domain' ), __('Reset Products button', 'BeRocket_AJAX_domain'), __('Widget Type', 'BeRocket_AJAX_domain')), '</p>';
echo '<h3>', __( 'How to add update products button? (to filter products only after click on button)', 'BeRocket_AJAX_domain' ), '</h3>';
echo '<p>', sprintf(__( 'Select <strong>%s</strong> in step <strong>%s</strong>', 'BeRocket_AJAX_domain' ), __('Update Products button', 'BeRocket_AJAX_domain'), __('Widget Type', 'BeRocket_AJAX_domain')), '</p>';

?>
<script>
function braapf_set_wizard_widget_type() {
    setTimeout(function() {
        jQuery('#braapf_widget_type_filter').trigger('click');
    }, 500);
}
function braapf_set_wizard_filter_type() {
    jQuery('#braapf_filter_type').val('all_product_cat').trigger('change');
    jQuery('#braapf_style_color').trigger('click');
}
function berocket_aapf_single_filter_messages_list_start() {
    var elements = [
        {
            selector:'#titlediv',
            text:'<?php _e('Title will be displayed only on admin side.<br>You can write text, that want to mark it for admin side', 'BeRocket_AJAX_domain') ?>',
            disable_inside:false,
            execute:berocket_message_updater_execute,
            execute_after:berocket_message_updater_execute_after
        },
        {
            selector:'#conditions',
            text:'<?php _e('Where filters must be displayed.<br>Filter will be displayed on all pages if do not have conditions.<br><strong>Please first try to add filter without any condition to check that it works</strong>', 'BeRocket_AJAX_domain') ?>',
            disable_inside:false,
            execute:berocket_message_updater_execute,
            execute_after:berocket_message_updater_execute_after
        },
        {
            selector:'.braapf_filter_title',
            text:'<?php _e('Title will be displayed as widget title.<br>You can write text, that want to display above filter', 'BeRocket_AJAX_domain') ?>',
            disable_inside:false,
            execute:berocket_message_updater_execute,
            execute_after:berocket_message_updater_execute_after
        },
        {
            selector:'.braapf_widget_type',
            text:'<?php _e('All that can be displayed you can select there<h3>Variants:</h3><ul><li><strong>Filter</strong>(main type) - filters by attributes, categories, price etc.</li><li><strong>Update Products button</strong> - button to apply filters to products. Filter will applies only after click on update button</li><li><strong>Reset Products button</strong> - button to disable all selected filters</li><li><strong>Selected Filters area</strong> - display selected filters and provide possibility to disable it in one place</li></ul>', 'BeRocket_AJAX_domain') ?>',
            execute_after:braapf_set_wizard_widget_type
        },
        
        {
            selector:'.brsbs_attribute_setup .berocket_sbs_content',
            text:'<?php _e('Select attribute that you need to filter by.<br>You can use for filtering price, attributes, categories, tags etc', 'BeRocket_AJAX_domain') ?>'
        },
        {
            selector:'.brsbs_style .berocket_sbs_content',
            text:'<?php _e('Filter style. This option change how filter will be look like.', 'BeRocket_AJAX_domain') ?>',
            execute:braapf_set_wizard_filter_type
        },
        {
            selector:'.brsbs_required',
            text:'<?php _e('Filter required options.<br>Those options must be setuped, because filter can work incorrect without it.', 'BeRocket_AJAX_domain') ?>'
        },
        {
            selector:'.brsbs_additional .berocket_sbs_content',
            text:'<?php _e('Filter Additional options. You can add/change some elements for filter', 'BeRocket_AJAX_domain') ?>'
        },
        {
            selector:'#meta_box_shortcode',
            text:'<?php _e('Shortcode to use this filters in any place of your site will be displayed there<br><strong>Please use widgets if you do not know how shortcode works</strong>', 'BeRocket_AJAX_domain') ?>',
        },
        {
            selector:'.brsbs_save .button',
            text:'<?php _e('Save filter after setup to use it in widgets', 'BeRocket_AJAX_domain') ?>',
        },
    ];
    if( jQuery('#setup_widget').length ) {
        elements.push({
            selector:'#setup_widget',
            text:'<?php _e('You can use widget to display filters on your shop page.<br><strong>Use sidebar, that displayed on shop page</strong>', 'BeRocket_AJAX_domain') ?>'
        });
    }
    berocket_blocks_messages(elements);
}
function berocket_aapf_single_filter_messages_list_add_widget() {
    var elements = [
        {
            selector:'#setup_widget',
            text:'<?php _e('You can use widget to display filters on your shop page.<br><strong>Use sidebar, that displayed on shop page</strong>', 'BeRocket_AJAX_domain') ?>'
        },
        {
            selector:'#meta_box_shortcode',
            text:'<?php _e('Shortcode to use this filters in any place of your site will be displayed there<br><strong>Please use widgets if you do not know how shortcode works</strong>', 'BeRocket_AJAX_domain') ?>',
        }
    ];
    berocket_blocks_messages(elements);
}
function berocket_message_updater_execute() {
    jQuery(document).on('click', berocket_display_block_message_reload_last);
    jQuery('#post').on('submit', berocket_event_prevent_default);
}
function berocket_message_updater_execute_after() {
    jQuery(document).off('click', berocket_display_block_message_reload_last);
    jQuery('#post').off('submit', berocket_event_prevent_default);
}
function berocket_event_prevent_default(event) {
    event.preventDefault();
}
jQuery(document).on('click', '.berocket_aapf_filter_setup_wizard', berocket_aapf_single_filter_messages_list_start);
<?php
    global $pagenow;
    $setup_wizard = get_option('berocket_aapf_filters_setup_wizard_list');
    if( ! is_array($setup_wizard) ) {
        $setup_wizard = array();
    }
    $single_filter_wizard = br_get_value_from_array($setup_wizard, 'single_filter');
    if( (in_array( $pagenow, array( 'post-new.php' ) ) && $single_filter_wizard < 1) || br_get_value_from_array($_GET, 'aapf') == 'singlewizard' ) {
        $setup_wizard['single_filter'] = -1;
        update_option('berocket_aapf_filters_setup_wizard_list', $setup_wizard);
        echo 'jQuery(document).ready(function(){setTimeout(berocket_aapf_single_filter_messages_list_start, 1000);});';
    } else if( $single_filter_wizard == 1 ) {
        $setup_wizard['single_filter'] = 2;
        update_option('berocket_aapf_filters_setup_wizard_list', $setup_wizard);
        echo 'jQuery(document).ready(function(){setTimeout(berocket_aapf_single_filter_messages_list_add_widget, 1000);});';
    }
?>
</script>
