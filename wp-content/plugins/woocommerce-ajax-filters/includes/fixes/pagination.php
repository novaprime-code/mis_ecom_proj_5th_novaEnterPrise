<?php
if( ! class_exists('berocket_aapf_pagination_not_displayed_fix') ) {
    class berocket_aapf_pagination_not_displayed_fix {
        function __construct() {
            add_action('woocommerce_after_template_part', array($this, 'displayed_fix'), 10, 4);
            add_filter('aapf_localize_widget_script', array($this, 'add_pagiantion_class'));
            add_action('wp_footer', array($this, 'wp_footer'));
        }
        function displayed_fix($template_name, $template_path, $located, $args) {
            if( $template_name == 'loop/pagination.php' ) {
                extract( $args );
                $total = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
                if ( $total <= 1 ) {
                    echo '<div class="bapf_pagination_replace" style="display:none;"></div>';
                }
            }
        }
        function add_pagiantion_class($the_ajax_script) {
            $the_ajax_script['pagination_class'] = $the_ajax_script['pagination_class'] . ', .bapf_pagination_replace';
            return $the_ajax_script;
        }
        function wp_footer() {
            ?>
<script>
berocket_aapf_pagination_not_displayed_fix = function(args) {
    console.log(args);
    if( typeof(args.type) != 'undefined' && args.type == 'pagination' ) {
        args.replace = true;
    }
    return args;
}
if ( typeof(berocket_add_filter) == 'function' ) {
    berocket_add_filter('replace_current_with_new_args', berocket_aapf_pagination_not_displayed_fix);
} else {
    jQuery(document).on('berocket_hooks_ready', function() {
        berocket_add_filter('replace_current_with_new_args', berocket_aapf_pagination_not_displayed_fix);
    });
}
</script>
            <?php
        }
    }
    new berocket_aapf_pagination_not_displayed_fix();
}