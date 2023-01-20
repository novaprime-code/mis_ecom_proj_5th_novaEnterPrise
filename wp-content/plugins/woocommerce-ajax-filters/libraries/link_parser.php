<?php
class BeRocket_AAPF_link_parser {
    public $taxonomy_changer = array();
    function __construct() {
        $this->taxonomy_changer = apply_filters('BR_AAPF_link_parser_taxonomy_changer', array(
            '_stock_status' => array(
                'taxonomy'  => '_stock_status',
                'terms'     => array(
                    0 => '',
                    1 => 'instock',
                    2 => 'outofstock'
                )
            ),
            '_sale'         => array(
                'taxonomy'  => '_sale',
                'terms'     => array(
                    0 => '',
                    1 => 'sale',
                    2 => 'notsale'
                )
            ),
            '_rating'       => array(
                'taxonomy' => 'product_visibility',
            ),
            'price'         => array(
                'taxonomy' => 'price'
            )
        ), $this);
        if( ! is_admin() ) {
            add_action( 'braapf_wp_enqueue_script_after', array($this, 'js_generate'), 10, 1 );
        }
        add_filter('bapf_uparse_parse_get_filter_line', array($this, 'get_filter_line'), 100, 3);
    }
    function js_generate($handle) {
        if( $handle == 'berocket_aapf_widget-script' ) {
            ob_start();
            $this->js_generate_inside();
            $script = ob_get_clean();
            wp_add_inline_script('berocket_aapf_widget-script', $script);
            remove_action( 'braapf_wp_enqueue_script_after', array($this, 'js_generate'), 10, 1 );
        }
    }
    function js_generate_inside() {}
    function get_filter_line($result, $instance, $link = false) {
        return $result;
    }
    function check_taxonomy($taxonomy) {
        if( taxonomy_exists( 'pa_'.$taxonomy ) ) {
            return 'pa_'.$taxonomy;
        } elseif( taxonomy_exists( $taxonomy ) ) {
            return $taxonomy;
        } elseif ( array_key_exists($taxonomy, $this->taxonomy_changer) ) {
            return $this->taxonomy_changer[$taxonomy]['taxonomy'];
        }
        return apply_filters('bapf_link_parser_check_taxonomy', false, $taxonomy);
    }
}
