<?php
/* $post_settings example
array(
   'labels' => array(
       'menu_name'          => _x( 'Product Filters', 'Admin menu name', 'BeRocket_AJAX_domain' ),
       'add_new_item'       => __( 'Add New Filter', 'BeRocket_AJAX_domain' ),
       'edit'               => __( 'Edit', 'BeRocket_AJAX_domain' ),
       'edit_item'          => __( 'Edit Filter', 'BeRocket_AJAX_domain' ),
       'new_item'           => __( 'New Filter', 'BeRocket_AJAX_domain' ),
       'view'               => __( 'View Filters', 'BeRocket_AJAX_domain' ),
       'view_item'          => __( 'View Filter', 'BeRocket_AJAX_domain' ),
       'search_items'       => __( 'Search Product Filters', 'BeRocket_AJAX_domain' ),
       'not_found'          => __( 'No Product Filters found', 'BeRocket_AJAX_domain' ),
       'not_found_in_trash' => __( 'No Product Filters found in trash', 'BeRocket_AJAX_domain' ),
   ),
   'description'     => __( 'This is where you can add Product Filters.', 'BeRocket_AJAX_domain' ),
   'public'          => true,
   'show_ui'         => true,
   'capability_type' => 'post',
   'publicly_queryable'  => false,
   'exclude_from_search' => true,
   'show_in_menu'        => 'edit.php?post_type=product',
   'hierarchical'        => false,
   'rewrite'             => false,
   'query_var'           => false,
   'supports'            => array( 'title' ),
   'show_in_nav_menus'   => false,
)
*/

if ( ! class_exists('BeRocket_custom_post_class') ) {
    class BeRocket_custom_post_class {
        public $meta_boxes = array();
        public $default_settings = array();
        public $post_settings, $post_name;
        public $post_type_parameters = array();
        public $addons = array();
        protected static $instance;

        public static function getInstance() {
            if (null === static::$instance)
            {
                static::$instance = new static();
            }
            return static::$instance;
        }

        function __construct () {
            if (null === static::$instance)
            {
                static::$instance = $this;
            }
            $this->post_type_parameters = array_merge(array(
                'sortable' => false,
                'can_be_disabled' => false,
            ), $this->post_type_parameters);
            add_filter( 'init', array( $this, 'init' ) );
            add_filter( 'admin_init', array( $this, 'admin_init' ), 15 );
            add_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 30, 2 );
            if( $this->post_type_parameters['sortable'] ) {
                include_once('custom_post/sortable.php');
                $addons['sortable'] = new BeRocket_custom_post_sortable_addon_class($this);
            }
            if( $this->post_type_parameters['can_be_disabled'] ) {
                include_once('custom_post/enable_disable.php');
                $addons['can_be_disabled'] = new BeRocket_custom_post_enable_disable_addon_class($this);
            }
            if( ! empty($this->post_settings['capability_type']) && $this->post_settings['capability_type'] != 'product' ) {
                add_filter('BeRocket_admin_init_user_capabilities', array($this, 'init_user_capabilities'));
            }
        }

        function init() {
            $this->default_settings = apply_filters('berocket_custom_post_'.$this->post_name.'_default_settings', $this->default_settings, self::$instance);
            register_post_type( $this->post_name, $this->post_settings);
        }

        public function get_custom_posts($args = array()) {
            $args = array_merge(apply_filters( 'berocket_custom_post_'.$this->post_name.'_get_custom_posts_args_default', array(
                'posts_per_page'   => -1,
                'offset'           => 0,
                'category'         => '',
                'category_name'    => '',
                'include'          => '',
                'exclude'          => '',
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => $this->post_name,
                'post_mime_type'   => '',
                'post_parent'      => '',
                'author'           => '',
                'post_status'      => 'publish',
                'fields'           => 'ids',
                'suppress_filters' => false 
            ) ), $args);
            $posts_array = new WP_Query($args);
            $posts_array = $posts_array->posts;
            return $posts_array;
        }

        public function get_custom_posts_frontend($args = array(), $additional = array()) {
            $args = apply_filters('berocket_custom_post_'.$this->post_name.'_get_custom_posts_args_frontend', $args, $additional);
            return $this->get_custom_posts($args);
        }

        public function add_meta_box($slug, $name, $callback = false, $position = 'normal', $priority = 'high') {
            if( $callback === false ) {
                $callback = array($this, $slug);
            }
            $this->meta_boxes[$slug] = array('slug' => $slug, 'name' => $name, 'callback' => $callback, 'position' => $position, 'priority' => $priority);
        }

        public function admin_init() {
            global $pagenow;
            if( 'edit.php' == $pagenow && isset( $_GET['post_type'] ) && $_GET['post_type'] == $this->post_name ){
                wp_enqueue_script( 'berocket_framework_admin' );
                wp_enqueue_style( 'berocket_framework_admin_style' );
                do_action('berocket_custom_post_'.$this->post_name.'_admin_init_only', $this->post_type_parameters);
            }
            add_filter( 'bulk_actions-edit-'.$this->post_name, array( $this, 'bulk_actions_edit' ) );
            add_filter( 'views_edit-'.$this->post_name, array( $this, 'views_edit' ) );
            add_filter( 'manage_edit-'.$this->post_name.'_columns', array( $this, 'manage_edit_columns' ) );
            add_action( 'manage_'.$this->post_name.'_posts_custom_column', array( $this, 'columns_replace' ), 2 );
            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
            add_action( 'save_post', array( $this, 'wc_save_product' ), 10, 2 );
            add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
            add_filter( 'list_table_primary_column', array( $this, 'list_table_primary_column' ), 10, 2 );

            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            do_action( 'berocket_custom_post_'.$this->post_name.'_admin_init', $this->post_type_parameters);
        }

        public function admin_enqueue_scripts() {
            global $post;
            if ( ! empty( $post ) and $post->post_type == $this->post_name ) {
                wp_register_style( 'font-awesome', plugins_url( '../assets/css/font-awesome.min.css', __FILE__ ) );
				add_action('admin_footer', array($this, 'wp_footer_remove_notice'));
            }
        }
		public function wp_footer_remove_notice() {
			echo '<style>.notice:not(.berocket_admin_notice){display:none!important;}</style>';
		}

        public function post_row_actions($actions, $post) {
            if( $post->post_type == $this->post_name ) {
                if( isset($actions['inline hide-if-no-js']) ) {
                    unset($actions['inline hide-if-no-js']);
                }
                if ( current_user_can( 'delete_post', $post->ID ) ) {
                    if( $this->post_type_parameters['can_be_disabled'] ) {
                        if( has_term('isdisabled', 'berocket_taxonomy_data', $post) ) {
                            $actions['enable'] = sprintf(
                                '<a href="%s" class="submitdelete aria-button-if-js" aria-label="%s">%s</a>',
                                wp_nonce_url( "post.php?action=enable&amp;post=$post->ID", 'enable-post_' . $post->ID ),
                                esc_attr( __( 'Enable', 'BeRocket_domain') ),
                                _x( 'Enable', 'BeRocket_domain')
                            );
                        } else {
                            $actions['disable'] = sprintf(
                                '<a href="%s" class="submitdelete aria-button-if-js" aria-label="%s">%s</a>',
                                wp_nonce_url( "post.php?action=disable&amp;post=$post->ID", 'disable-post_' . $post->ID ),
                                esc_attr( __( 'Disable', 'BeRocket_domain') ),
                                _x( 'Disable', 'BeRocket_domain')
                            );
                        }
                    }
                }
            }
            return $actions;
        }

        public function list_table_primary_column($default, $screen_id) {
            if( $screen_id == 'edit-'.$this->post_name ) {
                $default = 'name';
            }
            return $default;
        }

        public function bulk_actions_edit ( $actions ) {
            unset( $actions['edit'] );
            return $actions;
        }

        public function views_edit ( $view ) {
            unset( $view['publish'], $view['private'], $view['future'] );
            return $view;
        }

        public function manage_edit_columns ( $columns ) {
            $columns = array();
            $columns["cb"]   = '<input type="checkbox" />';
            $columns["name"] = __( "Name", 'BeRocket_domain' );
            $columns = apply_filters( 'berocket_custom_post_'.$this->post_name.'_manage_edit_columns', $columns, $this->post_type_parameters);
            return $columns;
        }

        public function columns_replace ( $column ) {
            global $post;
            switch ( $column ) {
                case "name":

                    $edit_link = get_edit_post_link( $post->ID );
                    $title = '<a class="row-title" href="' . $edit_link . '">' . _draft_or_post_title() . '</a>';

                    echo 'ID:' . $post->ID . ' <strong>' . $title . '</strong>';

                    break;
                default:
                    break;
            }
            do_action( 'berocket_custom_post_'.$this->post_name.'_columns_replace', $column, $this->post_type_parameters);
        }

        public  function add_meta_boxes () {
            add_meta_box( 'submitdiv', __( 'Save content', 'BeRocket_domain' ), array( $this, 'save_meta_box' ), $this->post_name, 'side', 'high' );
            add_meta_box( 'copysettingsfromdiv', __( 'Copy settings from', 'BeRocket_domain' ), array( $this, 'copy_settings_from' ), $this->post_name, 'side', 'high' );
            foreach($this->meta_boxes as $meta_box) {
                add_meta_box( $meta_box['slug'], $meta_box['name'], $meta_box['callback'], $this->post_name, $meta_box['position'], $meta_box['priority'] );
            }
        }

        public function copy_settings_from($post) {
            $posts_array = $this->get_custom_posts();
            ?>
            <div class="berocket_copy_from_custom_post_block">
                <?php do_action('berocket_copy_from_custom_post_block', $this->post_name, $post);?>
                <select name="berocket_copy_from_custom_post_select">
                    <option value="0"><?php _e( 'Do not copy', 'BeRocket_domain' ) ?></option>
                    <?php
                    if( ! empty($posts_array) && is_array($posts_array) ) {
                        foreach($posts_array as $post_id) {
                            if( $post_id == $post->ID ) continue;
                            echo '<option value="'.$post_id.'">(ID: '.$post_id.') '.get_the_title($post_id).'</option>';
                        }
                    }
                    ?>
                </select>
                <input name="berocket_copy_from_custom_post" type="hidden" value="">
                <button type="button" class="button" disabled><?php _e( 'Copy', 'BeRocket_domain' ) ?></button>
            </div>
            <script>
                jQuery('.berocket_copy_from_custom_post_block button').on('click', function() {
                    jQuery('.berocket_copy_from_custom_post_block input').val(jQuery('.berocket_copy_from_custom_post_block select').val());
                    jQuery('.submitbox input[type=submit]').trigger('click');
                });
                jQuery('.berocket_copy_from_custom_post_block select').on('change', function() {
                        jQuery('.berocket_copy_from_custom_post_block button').prop('disabled', ( ! jQuery(this).val() || jQuery(this).val() == '0' ));

                });
            </script>
            <?php
        }

        public  function save_meta_box($post) {
            wp_enqueue_script( 'berocket_aapf_widget-colorpicker' );
            wp_enqueue_script( 'berocket_aapf_widget-admin' );
            wp_enqueue_style( 'brjsf-ui' );
            wp_enqueue_script( 'brjsf-ui' );
            wp_enqueue_script( 'berocket_framework_admin' );
            wp_enqueue_style( 'berocket_framework_admin_style' );
            wp_enqueue_script( 'berocket_widget-colorpicker' );
            wp_enqueue_style( 'berocket_widget-colorpicker-style' );
            wp_enqueue_style( 'font-awesome' );
            ?>
            <div class="submitbox" id="submitpost">

                <div id="minor-publishing">
                    <div id="major-publishing-actions">
                        <div id="delete-action">
                            <?php
                            global $pagenow;
                            if( ! in_array( $pagenow, array( 'post-new.php' ) ) ) {
                                if ( current_user_can( "delete_post", $post->ID ) ) {
                                    if ( ! EMPTY_TRASH_DAYS )
                                        $delete_text = __( 'Delete Permanently', 'BeRocket_domain' );
                                    else
                                        $delete_text = __( 'Move to Trash', 'BeRocket_domain' );
                                    ?>
                                    <a class="submitdelete deletion" href="<?php echo esc_url( get_delete_post_link( $post->ID ) ); ?>"><?php echo esc_attr( $delete_text ); ?></a>
                                <?php 
                                }
                            } ?>
                        </div>

                        <div id="publishing-action">
                            <span class="spinner"></span>
                            <input type="submit" class="button button-primary tips" name="publish" value="<?php _e( 'Save', 'BeRocket_domain' ); ?>" data-tip="<?php _e( 'Save/update notice', 'BeRocket_domain' ); ?>" />
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <?php
            wp_nonce_field($this->post_name.'_check', $this->post_name.'_nonce');
        }

        public function wc_save_check($post_id, $post) {
            if ( $this->post_name != $post->post_type ) {
                return false;
            }

            $current_settings = get_post_meta( $post_id, $this->post_name, true );

            if( empty($current_settings) ) {
                update_post_meta( $post_id, $this->post_name, $this->default_settings );
            }

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return false;
            }

            if( empty($_REQUEST[$this->post_name.'_nonce']) || ! wp_verify_nonce($_REQUEST[$this->post_name.'_nonce'], $this->post_name.'_check') ) {
                return false;
            }

            return true;
        }

        public function wc_save_product( $post_id, $post ) {
            do_action( 'berocket_custom_post_'.$this->post_name.'_wc_save_product_before', $post_id, $post, $this->post_type_parameters);
            if ( ! $this->wc_save_check( $post_id, $post ) ) {
                return false;
            }
            $this->wc_save_product_without_check($post_id, $post);
            do_action( 'berocket_custom_post_'.$this->post_name.'_wc_save_product_after', $post_id, $post, $this->post_type_parameters);
        }
        public function wc_save_product_without_check( $post_id, $post ) {
            do_action( 'berocket_custom_post_'.$this->post_name.'_wc_save_product_without_check_before', $post_id, $post, $this->post_type_parameters);
            if ( ! empty( $_POST[$this->post_name] ) ) {
                $previous_options = $this->get_option($post_id);
                $post_data = berocket_sanitize_array($_POST[$this->post_name], array($this->post_name), $previous_options);

                if( is_array($post_data) ) {
                    $settings = BeRocket_Framework::recursive_array_set($this->default_settings, $post_data);
                } else {
                    $settings = $post_data;
                }

                update_post_meta( $post_id, $this->post_name, $settings );
            }

            if ( ! empty($_POST['berocket_copy_from_custom_post']) ) {
                $copy_option = get_post_meta( $_POST['berocket_copy_from_custom_post'], $this->post_name, true );

                if( ! empty($copy_option) ) {
                    update_post_meta( $post_id, $this->post_name, $copy_option );
                    $_POST[$this->post_name] = $copy_option;
                    do_action('berocket_copy_from_custom_post', $this->post_name, $post_id, $post);
                }
            }
            do_action( 'berocket_custom_post_'.$this->post_name.'_wc_save_product_without_check_after', $post_id, $post, $this->post_type_parameters);
        }

        public function get_option( $post_id ) {
            $options = get_post_meta( $post_id, $this->post_name, true );

            if ( ! is_array($options) ) {
                $options = array();
            }

            $options = array_merge( $this->default_settings, $options );

            return $options;
        }

        public function create_new_post($post_data = array(), $options = array()) {
            $post_data = array_merge(array(
                'post_title'    => '',
                'post_content'  => '',
                'post_type'     => $this->post_name,
                'post_status'   => 'publish'
            ), $post_data);
            $post_id = wp_insert_post($post_data);
            $options = array_merge($this->default_settings, $options);
            $_POST[$this->post_name] = $options;
            $post = get_post($post_id);
            $this->wc_save_product_without_check($post_id, $post);
            return $post_id;
        }
        public function wp_insert_post_data($data, $post) {
            if ( ! isset($post['ID']) || ! $post['ID'] ) return $data;
            if ( $post['post_type'] !== $this->post_name ) return $data;
            if( ! in_array($data['post_status'], array('publish', 'trash')) ) {
                $data['post_status'] = 'publish';
            }
            return $data;
        }
        public function init_user_capabilities($user_caps) {
            $cap_settings = $this->post_settings;
            $cap_settings['capabilities'] = array();
            if( ! isset($cap_settings['map_meta_cap']) ) {
                $cap_settings['map_meta_cap'] = true;
            }
            $post_caps = get_post_type_capabilities((object)$cap_settings);
            $post_caps = (array)$post_caps;
            foreach($post_caps as $post_cap) {
                $user_caps[] = $post_cap;
            }
            return $user_caps;
        }
    }
}
