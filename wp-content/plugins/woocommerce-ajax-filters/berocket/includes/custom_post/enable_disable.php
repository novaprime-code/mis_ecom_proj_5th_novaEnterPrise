<?php
if ( ! class_exists('BeRocket_custom_post_enable_disable_addon_class') ) {
    class BeRocket_custom_post_enable_disable_addon_class {
        public $post_name;
        public $custom_post;
        function __construct($custom_post) {
            $this->post_name = $custom_post->post_name;
            $this->custom_post = $custom_post;
            add_action('init', array($this, 'register_disabled_taxonomy'), 10);
            add_action('admin_init', array($this, 'add_disabled_term'), 20);
            add_filter( 'bulk_actions-edit-'.$this->post_name, array($this, 'disable_bulk_action_dropdown') );
            add_action('handle_bulk_actions-edit-'.$this->post_name, array($this, 'disable_bulk_actions'), 10, 3);
            add_action('post_action_enable', array($this, 'post_action_enable'));
            add_action('post_action_disable', array($this, 'post_action_disable'));
            add_filter('views_edit-'.$this->post_name, array($this, 'post_filter_isdisabled_menu'));
            add_filter('post_class', array($this, 'disable_post_class'), 10, 3);
            if( isset($_GET['brdisabled']) && berocket_isset($_GET['post_type']) == $this->post_name ) {
                add_filter('pre_get_posts',array($this, 'post_filter_isdisabled'));
            }
            add_filter('berocket_custom_post_'.$this->post_name.'_get_custom_posts_args_frontend', array($this, 'get_custom_posts_frontend'), 10, 2);
        }
        public function register_disabled_taxonomy() {
            register_taxonomy( 'berocket_taxonomy_data', $this->post_name);
        }
        public function add_disabled_term() {
			if ( term_exists( 'isdisabled', 'berocket_taxonomy_data' ) ) {
			    return;
		    }
            wp_insert_term( 'isdisabled', 'berocket_taxonomy_data', array(
                'description' => '',
                'parent'      => 0,
                'slug'        => 'isdisabled',
            ) );
        }
        public function disable_bulk_action_dropdown($actions) {
            if( ! isset($_GET['brdisabled']) || $_GET['brdisabled'] == 1 ) {
                $actions['enable'] = __( 'Enable', 'BeRocket_domain');
            }
            if( ! isset($_GET['brdisabled']) || $_GET['brdisabled'] == 0 ) {
                $actions['disable'] = __( 'Disable', 'BeRocket_domain');
            }
            return $actions;
        }
        public function change_post_isdisabled($post_id, $doaction) {
            $ischanged = false;
            if( $doaction == 'enable' && has_term('isdisabled', 'berocket_taxonomy_data', $post_id) ) {
                wp_remove_object_terms($post_id, 'isdisabled', 'berocket_taxonomy_data');
                $ischanged = true;
            }
            if( $doaction == 'disable' && ! has_term('isdisabled', 'berocket_taxonomy_data', $post_id) ) {
                wp_set_post_terms( $post_id, 'isdisabled', 'berocket_taxonomy_data', true );
                $ischanged = true;
            }
            return $ischanged;
        }
        public function disable_bulk_actions($sendback, $doaction, $post_ids) {
            if ( $doaction !== 'enable' && $doaction !== 'disable' ) {
                return $sendback;
            }
            $count = 0;
            foreach ( (array) $post_ids as $post_id ) {
                if ( ! current_user_can( 'delete_post', $post_id ) ) {
                    wp_die( __( 'Sorry, you are not allowed to change this item status.', 'BeRocket_domain' ) );
                }
                if( $this->change_post_isdisabled($post_id, $doaction) ) {
                    $count++;
                }
            }
            $sendback = add_query_arg(
                array(
                    ($doaction == 'disable' ? 'disabled' : 'enabled') => $count,
                    'ids'     => join( ',', $post_ids ),
                ),
                $sendback
            );
            return $sendback;
        }
        public function post_action_isdisabled_change($post_id, $doaction) {
            global $post_type, $post_type_object, $post;
            if( $post_type != $this->post_name ) return;
            check_admin_referer( $doaction.'-post_' . $post_id );
            $sendback = wp_get_referer();
            if ( ! $post ) {
                wp_die( __( 'The item you are trying to change status no longer exists.', 'BeRocket_domain' ) );
            }
            if ( ! $post_type_object ) {
                wp_die( __( 'Invalid post type.' ) );
            }
            if ( ! current_user_can( 'delete_post', $post_id ) ) {
                wp_die( __( 'Sorry, you are not allowed to change this item status.', 'BeRocket_domain' ) );
            }
            $this->change_post_isdisabled($post_id, $doaction);

            wp_redirect(
                add_query_arg(
                    array(
                        ($doaction == 'disable' ? 'disabled' : 'enabled') => 1,
                        'ids'     => $post_id,
                    ),
                    $sendback
                )
            );
            exit();
        }
        public function disable_post_class($classes, $class, $post_id) {
            global $post_type;
            if( $post_type == $this->post_name ) {
                if( has_term('isdisabled', 'berocket_taxonomy_data', $post_id) ) {
                    $classes[] = 'berocket_disabled_post';
                } else {
                    $classes[] = 'berocket_enabled_post';
                }
            }
            return $classes;
        }
        public function post_action_enable($post_id) {
            $this->post_action_isdisabled_change($post_id, 'enable');
        }
        public function post_action_disable($post_id) {
            $this->post_action_isdisabled_change($post_id, 'disable');
        }
        public function post_filter_isdisabled_menu($views) {
            global $post_type;
            if( $post_type == $this->post_name ) {
                $url = add_query_arg( array('post_type' => $post_type, 'brdisabled' => 0), 'edit.php' );
                $class = (( isset($_GET['brdisabled']) && $_GET['brdisabled'] == 0 ) ? ' class="current"' : '');
                $views['enabled'] = sprintf(
                    '<a href="%s"%s>%s</a>',
                    esc_url( $url ),
                    $class,
                    __('Enabled', 'BeRocket_domain')
                );
                $url = add_query_arg( array('post_type' => $post_type, 'brdisabled' => 1), 'edit.php' );
                $class = (( isset($_GET['brdisabled']) && $_GET['brdisabled'] == 1 ) ? ' class="current"' : '');
                $views['disabled'] = sprintf(
                    '<a href="%s"%s>%s</a>',
                    esc_url( $url ),
                    $class,
                    __('Disabled', 'BeRocket_domain')
                );
            }
            return $views;
        }
        public function post_filter_isdisabled($query) {
            if( ! $query->is_main_query() ) return $query;
            $tax_query = $query->get('tax_query');
            if( ! is_array($tax_query) ) {
                $tax_query = array();
            }
            $tax_query[] = array(
                'taxonomy' => 'berocket_taxonomy_data',
                'field'    => 'slug',
                'terms'    => 'isdisabled',
                'operator' => (empty($_GET['brdisabled']) ? "NOT IN" : "IN")
            );
            $query->set('tax_query', $tax_query);
            return $query;
        }
        public function get_custom_posts_frontend($args = array(), $additional = array()) {
            $additional = array_merge(array(
                'hide_disabled' => true
            ), $additional);
            if( ! empty($additional['hide_disabled']) ) {
                if( empty($args['tax_query']) ) {
                    $args['tax_query'] = array();
                }
                $args['tax_query'][] = array(
                    'taxonomy' => 'berocket_taxonomy_data',
                    'field'    => 'slug',
                    'terms'    => 'isdisabled',
                    'operator' => "NOT IN"
                );
            }
            return $args;
        }
    }
}
