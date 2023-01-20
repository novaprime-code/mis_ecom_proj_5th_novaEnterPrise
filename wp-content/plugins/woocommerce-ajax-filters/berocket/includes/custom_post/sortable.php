<?php
if ( ! class_exists('BeRocket_custom_post_sortable_addon_class') ) {
    class BeRocket_custom_post_sortable_addon_class {
        public $post_name;
        public $custom_post;
        function __construct($custom_post) {
            $this->post_name = $custom_post->post_name;
            $this->custom_post = $custom_post;
            if( is_admin() ) {
                add_action('berocket_custom_post_'.$this->post_name.'_admin_init', array($this, 'sortable_admin_init'));
                add_action('berocket_custom_post_'.$this->post_name.'_wc_save_product_before', array($this, 'sortable_wc_save_product_before'), 10, 2);
                add_action('berocket_custom_post_'.$this->post_name.'_wc_save_product_without_check_before', array($this, 'sortable_wc_save_product_before'), 10, 2);
                add_action('berocket_custom_post_'.$this->post_name.'_columns_replace', array($this, 'sortable_columns_replace'), 10, 1);
                add_filter('berocket_custom_post_'.$this->post_name.'_manage_edit_columns', array($this, 'sortable_manage_edit_columns'));
                add_filter('manage_edit-'.$this->post_name.'_sortable_columns', array($this, 'sortable_columns_set'));
            }
            add_filter('berocket_custom_post_'.$this->post_name.'_get_custom_posts_args_default', array($this, 'sortable_get_custom_post'));
            add_action('berocket_custom_post_'.$this->post_name.'_admin_init_only', array($this, 'jquery_sortable_for_posts'));
        }
        public function sortable_admin_init() {
            $this->custom_post->get_custom_posts();
            add_action( 'pre_get_posts', array($this, 'sortable_get_posts'), 999999 );
            if( ! empty($_POST['braction']) && $_POST['braction'] == 'berocket_custom_post_sortable' ) {
                $this->sortable_change();
            }
        }
        public function sortable_change() {
            if( ! empty($_POST['BRsortable_id']) && isset($_POST['BRorder']) ) {
                $BRsortable_id = sanitize_key($_POST['BRsortable_id']);
                $BRorder = sanitize_key($_POST['BRorder']);
                $BRsortable_id = intval($BRsortable_id);
                $BRorder = intval($BRorder);
                if( current_user_can('edit_post', $BRsortable_id) ) {
                    update_post_meta($BRsortable_id, 'berocket_post_order', $BRorder);
                }
            }
            if( ! empty($_POST['BRsortable']) ) {
                $BRsortable = $_POST['BRsortable'];
                if( ! is_array($BRsortable) ) {
                    $BRsortable = array();
                }
                foreach($BRsortable as $BRsortable_post) {
                    $BRsortable_id = sanitize_key($BRsortable_post['id']);
                    $BRorder = sanitize_key($BRsortable_post['order']);
                    $BRsortable_id = intval($BRsortable_id);
                    $BRorder = intval($BRorder);
                    if( current_user_can('edit_post', $BRsortable_id) ) {
                        update_post_meta($BRsortable_id, 'berocket_post_order', $BRorder);
                    }
                }
            }
        }
        public function sortable_get_posts( $query ){
            global $pagenow;
            $post_type = $query->get('post_type');
            if( 'edit.php' == $pagenow && $post_type == $this->post_name && (empty($_GET['orderby']) || $_GET['orderby'] == 'berocket_sortable') ){
                $query->set( 'meta_key', 'berocket_post_order' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', ( (empty($_GET['order']) || strtoupper($_GET['order']) == 'ASC') ? 'ASC' : 'DESC' ) );
            }
        }
        public function sortable_get_custom_post($args) {
            if( is_admin() && $this->post_name == br_get_value_from_array($_GET,'post_type') ) {
                $posts_not_ordered = new WP_Query($args);
                $posts_not_ordered = $posts_not_ordered->posts;
            }
            $args = array_merge($args, array(
                'meta_key'         => 'berocket_post_order',
                'orderby'          => 'meta_value_num',
                'order'            => 'ASC',
            ));
            if( is_admin() && $this->post_name == br_get_value_from_array($_GET,'post_type') ) {
                $posts_ordered = new WP_Query($args);
                $posts_ordered = $posts_ordered->posts;
                $posts_fix = array_diff($posts_not_ordered, $posts_ordered);
                foreach($posts_fix as $post_fix_id) {
                    add_post_meta( $post_fix_id, 'berocket_post_order', '0', true );
                }
            }
            return $args;
        }
        public function sortable_wc_save_product_before( $post_id, $post ) {
            $order_position = get_post_meta( $post_id, 'berocket_post_order', true );
            $order_position = intval($order_position);
            update_post_meta( $post_id, 'berocket_post_order', $order_position );
        }
        public function sortable_columns_replace($column) {
            global $post;
            $post_id = $post->ID;
            $order_position = get_post_meta( $post_id, 'berocket_post_order', true );
            $order_position = intval($order_position);
            switch ( $column ) {
                case "berocket_sortable":
                    echo $this->sortable_html_position($post_id, $order_position);
                    break;
                default:
                    break;
            }
        }
        public function sortable_html_position($post_id, $order) {
            $html = '';
            if( $order > 0 ) {
                $html .= '<a href="#order-up" class="berocket_post_set_new_sortable" data-post_id="'.$post_id.'" data-order="'.($order - 1).'"><i class="fa fa-arrow-up"></i></a>';
            }
            $html .= '<span class="berocket_post_set_new_sortable_input"><input type="number" min="0" value="'.$order.'"><a class="berocket_post_set_new_sortable_set fa fa-arrow-circle-right" data-post_id="'.$post_id.'" href="#order-set"></a></span>';
            $html .= '<a href="#order-up" class="berocket_post_set_new_sortable" data-post_id="'.$post_id.'" data-order="'.($order + 1).'"><i class="fa fa-arrow-down"></i></a>';
            return $html;
        }
        public function sortable_manage_edit_columns($columns) {
            $columns["berocket_sortable"] = __( "Order", 'BeRocket_domain' );
            return $columns;
        }
        public function jquery_sortable_for_posts() {
            wp_enqueue_script('jquery-ui-sortable');
            add_action('in_admin_footer', array($this, 'sortable_in_admin_footer'));
        }
        public function sortable_in_admin_footer() {
            global $wp_query;
            if( $wp_query->is_main_query() && $wp_query->max_num_pages == 1 ) {
                ?>
                <script>
                    jQuery(document).ready(function() {
                        var BRsortable_jquery_ui = function() {
                            if( ! jQuery("#the-list").is(".ui-sortable") ) {
                                jQuery("#the-list .column-name").prepend(jQuery("<i class='fa fa-bars'></i>"));
                                jQuery("#the-list").sortable({
                                    handle:".fa-bars",
                                    axis: "y",
                                    stop: function() {
                                        jQuery("#the-list .berocket_post_set_new_sortable_input input").each(function(i, o) {
                                            jQuery(o).val(i);
                                        });
                                        var BRsortable = [];
                                        jQuery("#the-list .berocket_post_set_new_sortable_input").each(function() {
                                            BRsortable.push({id:jQuery(this).find(".berocket_post_set_new_sortable_set").data('post_id'), order:jQuery(this).find("input").val()});
                                        });
                                        jQuery.post(location.href, {braction:'berocket_custom_post_sortable', BRsortable:BRsortable}, function(html) {
                                            var $html = jQuery(html);
                                            var $tbody = $html.find('.berocket_post_set_new_sortable').first().parents('tbody').first();
                                            jQuery('.berocket_post_set_new_sortable').first().parents('tbody').first().replaceWith($tbody);
                                            jQuery(document).trigger('BRsortable_loaded_html');
                                        });
                                    }
                                });
                            }
                        }
                        BRsortable_jquery_ui();
                        jQuery(document).on("BRsortable_loaded_html", BRsortable_jquery_ui);
                    });
                </script>
                <?php
            }
        }
        public function sortable_columns_set($columns) {
            $columns['berocket_sortable'] = 'berocket_sortable';
            return $columns;
        }
    }
}
