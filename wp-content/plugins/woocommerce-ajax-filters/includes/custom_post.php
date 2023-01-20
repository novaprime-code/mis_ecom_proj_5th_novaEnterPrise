<?php
class BeRocket_conditions_AAPF extends BeRocket_conditions {
    public static function get_conditions() {
        $conditions = parent::get_conditions();
        $conditions['condition_page'] = array(
            'func' => 'check_condition_page',
            'type' => 'page',
            'name' => __('Page ID', 'BeRocket_domain')
        );
        
        $conditions['condition_user_status'] = array(
            'func' => 'check_condition_user_status',
            'type' => 'user_status',
            'name' => __('User Status', 'BeRocket_products_of_day_domain')
        );
        $conditions['condition_user_role'] = array(
            'func' => 'check_condition_user_role',
            'type' => 'user_role',
            'name' => __('User Role', 'BeRocket_products_of_day_domain')
        );
        return $conditions;
    }
    public static function condition_page($html, $name, $options) {
        $options = self::berocket_aapf_page_compatibility_fix($options);
        return parent::condition_page_id($html, $name, $options);
    }
    public static function check_condition_page($show, $condition, $additional) {
        $condition = self::berocket_aapf_page_compatibility_fix($condition);
        return parent::check_condition_page_id($show, $condition, $additional);
    }
    public static function berocket_aapf_page_compatibility_fix($options) {
        $compatibility_pages = array(
            'product_cat' => 'category',
            'product_taxonomy' => 'taxonomies',
            'product_tag' => 'tags',
            'single_product' => 'product',
        );
        if( ! empty($options['page']) ) {
            if( array_key_exists($options['page'], $compatibility_pages) ) {
                $options['page'] = $compatibility_pages[$options['page']];
            }
            $options['pages'] = array($options['page']);
        }
        return $options;
    }
    //User Status
    public static function condition_user_status($html, $name, $options) {
        $def_options = array('not_logged_page' => '', 'customer_page' => '', 'logged_page' => '');
        $options = array_merge($def_options, $options);
        $html .= '<p>
            <label><input type="checkbox" name="'.$name.'[not_logged_page]"'.(empty($options['not_logged_page']) ? '' : ' checked').'>'.__('Not Logged In', 'BeRocket_products_of_day_domain').'</label>
            <label><input type="checkbox" name="'.$name.'[customer_page]"'.(empty($options['customer_page']) ? '' : ' checked').'>'.__('Logged In Customers', 'BeRocket_products_of_day_domain').'</label>
            <label><input type="checkbox" name="'.$name.'[logged_page]"'.(empty($options['logged_page']) ? '' : ' checked').'>'.__('Logged In Not Customers', 'BeRocket_products_of_day_domain').'</label>
        </p>';
        return $html;
    }
    public static function check_condition_user_status($show, $condition, $additional) {
        $orders = get_posts( array(
            'meta_key'    => '_customer_user',
            'meta_value'  => get_current_user_id(),
            'post_type'   => 'shop_order',
            'post_status' => array( 'wc-processing', 'wc-completed' ),
        ) );
        $is_logged_in = is_user_logged_in();
        if( ! $is_logged_in ) {
            $show = ! empty($condition['not_logged_page']);
        } elseif( $orders ) {
            $show = ! empty($condition['customer_page']);
        } else {
            $show = ! empty($condition['logged_page']);
        }
        return $show;
    }
    public static function condition_user_role($html, $name, $options) {
        $def_options = array('role' => '');
        $options = array_merge($def_options, $options);
        $html .= static::supcondition($name, $options);
        $html .= '<select name="' . $name . '[role]">';
        $editable_roles = array_reverse( get_editable_roles() );
        foreach ( $editable_roles as $role => $details ) {
            $name = translate_user_role($details['name'] );
            $html .= "<option " . ($options['role'] == $role ? ' selected' : '') . " value='" . esc_attr( $role ) . "'>{$name}</option>";
        }
        $html .= '</select>';
        return $html;
    }
    public static function check_condition_user_role($show, $condition, $additional) {
        $post_author_id = get_current_user_id();
        $user_info = get_userdata($post_author_id);
        if( ! empty($user_info) ) {
            $show = in_array($condition['role'], $user_info->roles);
        } else {
            $show = false;
        }
        if( $condition['equal'] == 'not_equal' ) {
            $show = ! $show;
        }
        return $show;
    }
}
class BeRocket_AAPF_single_filter extends BeRocket_custom_post_class {
    public $hook_name = 'berocket_aapf_single_filter';
    public $conditions;
    protected static $instance;
    public $post_type_parameters = array(
        'can_be_disabled' => true
    );
    function __construct() {
        add_action('ajax_filters_framework_construct', array($this, 'init_conditions'));
        $this->post_name = 'br_product_filter';
        $this->post_settings = array(
            'label' => __( 'Product Filter', 'BeRocket_AJAX_domain' ),
            'labels' => array(
                'name'               => __( 'Product Filter', 'BeRocket_AJAX_domain' ),
                'singular_name'      => __( 'Product Filter', 'BeRocket_AJAX_domain' ),
                'menu_name'          => _x( 'Filters', 'Admin menu name', 'BeRocket_AJAX_domain' ),
                'add_new'            => __( 'Add Filter', 'BeRocket_AJAX_domain' ),
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
            'capability_type' => 'single_filter',
            'map_meta_cap'    => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_in_menu'        => 'berocket_account',
            'hierarchical'        => false,
            'rewrite'             => false,
            'query_var'           => false,
            'supports'            => array( 'title' ),
            'show_in_nav_menus'   => false,
        );
        $this->default_settings = array(
            'data'                          => array(),
            'br_wp_footer'                  => false,
            'widget_type'                   => '',
            'reset_hide'                    => 'berocket_no_filters',
            'title'                         => '',
            'filter_type'                   => 'attribute',
            'attribute'                     => '',
            'custom_taxonomy'               => 'product_cat',
            'type'                          => '',
            'select_first_element_text'     => '',
            'operator'                      => 'OR',
            'order_values_by'               => '',
            'order_values_type'             => '',
            'text_before_price'             => '',
            'text_after_price'              => '',
            'enable_slider_inputs'          => '',
            'parent_product_cat'            => '',
            'depth_count'                   => '0',
            'widget_collapse_enable'        => '0',
            'widget_is_hide'                => '0',
            'show_product_count_per_attr'   => '0',
            'hide_child_attributes'         => '0',
            'hide_collapse_arrow'           => '0',
            'use_value_with_color'          => '0',
            'values_per_row'                => '',
            'icon_before_title'             => '',
            'icon_after_title'              => '',
            'icon_before_value'             => '',
            'icon_after_value'              => '',
            'price_values'                  => '',
            'description'                   => '',
            'css_class'                     => '',
            'use_min_price'                 => '',
            'min_price'                     => '',
            'use_max_price'                 => '',
            'max_price'                     => '',
            'height'                        => '',
            'scroll_theme'                  => 'dark',
            'selected_area_show'            => '0',
            'hide_selected_arrow'           => '0',
            'selected_is_hide'              => '0',
            'slider_default'                => '0',
            'number_style'                  => '0',
            'number_style_thousand_separate'=> '',
            'number_style_decimal_separate' => '.',
            'number_style_decimal_number'   => '2',
            'is_hide_mobile'                => '0',
            'user_can_see'                  => '',
            'cat_propagation'               => '0',
            'product_cat'                   => '',
            'parent_product_cat_current'    => '0',
            'attribute_count'               => '',
        );
        $this->add_meta_box('conditions', __( 'Conditions', 'BeRocket_AJAX_domain' ));
        $this->add_meta_box('settings', __( 'Product Filter Settings', 'BeRocket_AJAX_domain' ));
        $this->add_meta_box('meta_box_shortcode', __( 'Shortcode', 'BeRocket_AJAX_domain' ), false, 'side');
        $this->add_meta_box('information_faq', __( 'FAQ', 'BeRocket_AJAX_domain' ), false, 'side');
        parent::__construct();
        add_shortcode( 'br_filter_single', array( $this, 'shortcode' ) );
        $setup_wizard = get_option('berocket_aapf_filters_setup_wizard_list');
        $single_filter_wizard = br_get_value_from_array($setup_wizard, 'single_filter');
        if( $single_filter_wizard > 0 ) {
            $this->add_meta_box('setup_widget', __( 'Setup Widget', 'BeRocket_AJAX_domain' ));
        }
        add_action('admin_head', array($this, 'admin_head'), 20);
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 20);
        add_filter( 'berocket_admin_filter_types_by_attr', array($this, 'admin_filter_types_by_attr'), 10, 2 );
    }
    public function admin_enqueue_scripts() {
        $current_screen = get_current_screen();
        if( berocket_isset($current_screen, 'post_type') == 'br_product_filter' ) {
            if( apply_filters('BRAAPF_single_filter_settings_enqueue_scripts', true) ) {
                BeRocket_AAPF::wp_enqueue_script('braapf-javascript-hide');
                BeRocket_AAPF::wp_enqueue_script('braapf-single-filter-edit');
                BeRocket_AAPF::wp_enqueue_style( 'braapf-single-filter-edit');
            }
        }
    }
    function admin_filter_types_by_attr($vars, $type = 'main') {
        list($berocket_admin_filter_types, $berocket_admin_filter_types_by_attr) = $vars;
        if( $type == 'simple' ) {
            $unsets = array('color', 'image');
            foreach($berocket_admin_filter_types as &$type) {
                foreach($unsets as $unset) {
                    if( ($position = array_search($unset, $type)) !== FALSE ) {
                        unset($type[$position]);
                        $type = array_values($type);
                    }
                }
            }
            if( isset($type) ) {
                unset($type);
            }
        }
        return array($berocket_admin_filter_types, $berocket_admin_filter_types_by_attr);
    }
    public function admin_head() {
        $screen = get_current_screen();
        if( berocket_isset($screen, 'id') == 'widgets' ) {
            $admin_js = '
            jQuery.each(berocket_admin_filter_types, function(i, val) {
                var position = val.indexOf("color");
                if( position != -1 ) {
                    val.splice(position, 1);
                }
                var position = val.indexOf("image");
                if( position != -1 ) {
                    val.splice(position, 1);
                }
                berocket_admin_filter_types[i] = val;
            });
            ';
            wp_add_inline_script('berocket_aapf_widget-admin', $admin_js);
        }
        if(!session_id()) {
            session_start();
        }
        $braapf_widget_wizard = br_get_value_from_array($_SESSION, 'braapf_widget_wizard');
        $setup_wizard = get_option('berocket_aapf_filters_setup_wizard_list');
        $single_filter_wizard = br_get_value_from_array($setup_wizard, 'single_filter');
        if( br_get_value_from_array($_GET, 'aapf') == 'wizard' || ($braapf_widget_wizard && $single_filter_wizard == 2) ) {
            if( ! is_array($setup_wizard) ) {
                $setup_wizard = array();
            }
            $single_filter_wizard = br_get_value_from_array($setup_wizard, 'single_filter');
            $setup_wizard['single_filter'] = 3;
            update_option('berocket_aapf_filters_setup_wizard_list', $setup_wizard);
            wp_enqueue_script( 'berocket_framework_admin' );

            wp_enqueue_style( 'berocket_framework_admin_style' );
            ?>
            <script>
                function berocket_aapf_single_filter_messages_list_widget() {
                    var elements = [
                        {
                            selector:"[id*=berocket_aapf_single-__i__]",
                            text:'<?php _e('Widget to display single filter in your sidebar. Add it to needed sidebar', 'BeRocket_AJAX_domain') ?>',
                        }
                    ];
                    berocket_blocks_messages(elements);
                }
                jQuery(document).ready(function() {
                    setTimeout(berocket_aapf_single_filter_messages_list_widget, 1000);
                    var berocket_aapf_single_length = jQuery("#widgets-right .widget[id*=berocket_aapf_single-]").length;
                    var berocket_aapf_single_blocks = jQuery("#widgets-right .widget[id*=berocket_aapf_single-]");
                    var berocket_aapf_single_blocks2 = false;
                    berocket_aapf_single_blocks.each(function() {
                        if( berocket_aapf_single_blocks2 !== false ) {
                            berocket_aapf_single_blocks2 += ',';
                        }
                        berocket_aapf_single_blocks2 += '#'+jQuery(this).attr('id');
                    });
                    var berocket_aapf_single_interval = setInterval(function() {
                        if( jQuery("#widgets-right .widget[id*=berocket_aapf_single-]").length > berocket_aapf_single_length ) {
                            var $element = false;
                            if( berocket_aapf_single_blocks2 === false ) {
                                $element = jQuery("#widgets-right .widget[id*=berocket_aapf_single-]").first();
                            } else {
                                jQuery("#widgets-right .widget[id*=berocket_aapf_single-]").each(function() {
                                    if( ! jQuery(this).is(berocket_aapf_single_blocks2) ) {
                                        $element = jQuery(this);
                                    }
                                });
                            }
                            if( $element !== false ) {
                                if( ! $element.parents('.ui-sortable-helper').length ) {
                                    setTimeout(function() {
                                        var elements = [
                                            {
                                                selector:$element.find('.berocketwizard_aapf_single_widget_filter_id'),
                                                text:'<?php _e('Select filter that must be displayed in sidebar', 'BeRocket_AJAX_domain') ?>',
                                                disable_inside:false,
                                            },
                                            {
                                                selector:$element.find('.widget-control-save'),
                                                text:'<?php _e('Save widget and check it on shop page', 'BeRocket_AJAX_domain') ?>',
                                                disable_inside:false,
                                            }
                                        ];
                                        berocket_blocks_messages(elements);
                                    }, 700);
                                    clearInterval(berocket_aapf_single_interval);
                                }
                            }
                        }
                    }, 1000);
                });
            </script>
            <?php
        }
    }
    public function init_conditions() {
        $this->conditions = new BeRocket_conditions_AAPF($this->post_name.'[data]', $this->hook_name, array(
            'condition_page',
            'condition_page_woo_category'
        ));
    }
    public function conditions($post) {
        $options = $this->get_option( $post->ID );
        echo $this->conditions->build($options['data']);
        ?>
        <div class="section_conditions_hide_this_on">
            <table>
                <tr>
                    <th><?php _e('Hide this filter on:', 'BeRocket_AJAX_domain'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" value="1" name="<?php echo $this->post_name; ?>[is_hide_mobile]"<?php if( ! empty($options['is_hide_mobile']) ) echo ' checked'; ?>>
                            <?php _e('Mobile', 'BeRocket_AJAX_domain'); ?>
                        </label>
                        <label>
                            <input type="checkbox" value="1" name="<?php echo $this->post_name; ?>[hide_group][tablet]"<?php if( ! empty($options['hide_group']['tablet']) ) echo ' checked'; ?>>
                            <?php _e('Tablet', 'BeRocket_AJAX_domain'); ?>
                        </label>
                        <label>
                            <input type="checkbox" value="1" name="<?php echo $this->post_name; ?>[hide_group][desktop]"<?php if( ! empty($options['hide_group']['desktop']) ) echo ' checked'; ?>>
                            <?php _e('Desktop', 'BeRocket_AJAX_domain'); ?>
                        </label>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    public function meta_box_shortcode($post) {
        global $pagenow;
        if( in_array( $pagenow, array( 'post-new.php' ) ) ) {
            _e( 'You need save it to get shortcode', 'BeRocket_AJAX_domain' );
        } else {
            echo "[br_filter_single filter_id={$post->ID}]";
        }
    }
    public function information_faq($post) {
        include AAPF_TEMPLATE_PATH . "filters_information.php";
    }
    public function settings($post) {
        if( apply_filters('BRAAPF_single_filter_settings_meta_use', true, $this, $post) ) {
            do_action('bapf_include_all_tempate_styles');
            $braapf_filter_setings = $this->get_option($post->ID);
            $braapf_filter_setings['settings_name'] = $this->post_name;
            $post_name = $this->post_name;
            include AAPF_TEMPLATE_PATH . "single_filter/all_steps.php"; 
        }
    }
    public function setup_widget($post) {
        echo '<p>'.__('Now you can use saved filters in widgets', 'BeRocket_AJAX_domain').'</p>';
        echo '<p>'.__('Add widget <strong>AAPF Filter Single</strong> on ', 'BeRocket_AJAX_domain')
            .'<a href="'.admin_url('widgets.php?aapf=wizard').'">'.__('WIDGET PAGE', 'BeRocket_AJAX_domain').'</a></p>';
        echo '<a href="'.admin_url('widgets.php?aapf=wizard').'"><img style="max-width:100%;" src="'.plugins_url( 'images/adding-widgets.gif', BeRocket_AJAX_filters_file ).'"></a>';
    }
    public function manage_edit_columns ( $columns ) {
        $columns = parent::manage_edit_columns($columns);
        $columns["data"] = __( "Data", 'BeRocket_AJAX_domain' );
        $columns["shortcode"] = __( "Shortcode", 'BeRocket_AJAX_domain' );
        return $columns;
    }
    public function columns_replace ( $column ) {
        parent::columns_replace($column);
        global $post;
        $filter = $this->get_option($post->ID);
        switch ( $column ) {
            case "data":
                $widget_types = array(
                    'filter'        => __('Filter', 'BeRocket_AJAX_domain'),
                    'update_button' => __('Update Products button', 'BeRocket_AJAX_domain'),
                    'reset_button'  => __('Reset Products button', 'BeRocket_AJAX_domain'),
                    'selected_area' => __('Selected Filters area', 'BeRocket_AJAX_domain'),
                    'search_box'    => __('Search Box (DEPRECATED)', 'BeRocket_AJAX_domain')
                );
                echo __('Widget type: ', 'BeRocket_AJAX_domain') . '<strong>' . ( isset($widget_types[$filter['widget_type']]) ? $widget_types[$filter['widget_type']] : esc_html($filter['widget_type']) ) . '</strong>';
                echo '<br>';
                if( $filter['widget_type'] == 'search_box' ) {
                    $search_type = array(
                        'attribute' => __('Attribute', 'BeRocket_AJAX_domain'),
                        'tag' => __('Tag', 'BeRocket_AJAX_domain'),
                        'custom_taxonomy' => __('Custom Taxonomy', 'BeRocket_AJAX_domain'),
                    );
                    $i = 1;
                    foreach($filter['search_box_attributes'] as $search_box) {
                        if( $i > $filter['search_box_count']) break;
                        echo $i . ') ';
                        if( $search_box['type'] == 'attribute' ) {
                            echo __('Attribute: ', 'BeRocket_AJAX_domain') . '<strong>' . esc_html($search_box['attribute']) . '</strong>';
                        } elseif( $search_box['type'] == 'custom_taxonomy' ) {
                            echo __('Custom Taxonomy: ', 'BeRocket_AJAX_domain') . '<strong>' . esc_html($search_box['custom_taxonomy']) . '</strong>';
                        } elseif( $search_box['type'] == 'tag' ) {
                            echo __('Tag', 'BeRocket_AJAX_domain');
                        }
                        echo '<br>';
                        $i++;
                    }
                } elseif( $filter['widget_type'] == 'filter' ) {
                    $specific_filter_type = array(
                        'price'             => array( 'name' => __('Price', 'BeRocket_AJAX_domain')),
                        '_stock_status'     => array( 'name' => __('Stock status', 'BeRocket_AJAX_domain')),
                        'all_product_cat'   => array( 'name' => __('Product Category', 'BeRocket_AJAX_domain')),
                        'tag'               => array( 'name' => __('Tag', 'BeRocket_AJAX_domain')),
                        'date'              => array( 'name' => __('Date', 'BeRocket_AJAX_domain')),
                        '_sale'             => array( 'name' => __('Sale', 'BeRocket_AJAX_domain')),
                        '_rating'           => array( 'name' => __('Rating', 'BeRocket_AJAX_domain')),
                        'product_cat'       => array( 'name' => __('Product sub-categories', 'BeRocket_AJAX_domain')),
                    );
                    $taxonomies_display_data = array(
                        'attribute'  => array(
                            'name'      => __('Attribute: ', 'BeRocket_AJAX_domain'),
                            'value'     => 'attribute',
                            'error'     => __('Attribute not exists. This filter can work incorrect', 'BeRocket_AJAX_domain')
                        ),
                        'custom_taxonomy'  => array(
                            'name'      => __('Custom Taxonomy: ', 'BeRocket_AJAX_domain'),
                            'value'     => 'custom_taxonomy',
                            'error'     => __('Custom taxonomy not exists. This filter can work incorrect', 'BeRocket_AJAX_domain')
                        ),
                    );
                    if( isset($specific_filter_type[$filter['filter_type']]) ) {
                        echo '<strong>' . $specific_filter_type[$filter['filter_type']]['name'] . '</strong>';
                    } elseif( in_array($filter['filter_type'], array('attribute', 'custom_taxonomy')) ) {
                        $data_get = $taxonomies_display_data[$filter['filter_type']];
                        $taxonomy_details = get_taxonomy( $filter[$data_get['value']] );
                        if( ! empty($taxonomy_details) ) {
                            $taxonomy_details_label = $taxonomy_details->label;
                            echo $data_get['name'] . '<strong>' . $taxonomy_details_label . '</strong>';
                        } else {
                            if( $filter['filter_type'] != 'attribute' || $filter['attribute'] != 'price' ) { 
                                echo '<strong style="color:red;">' . $data_get['error'] . '</strong>';
                            }
                        }
                    }
                }
                break;
            case "shortcode":
                echo "[br_filter_single filter_id={$post->ID}]";
                break;
            default:
                break;
        }
    }
    public function shortcode($atts = array()) {
        ob_start();
        the_widget( 'BeRocket_new_AAPF_Widget_single', $atts);
        return ob_get_clean();
    }
    public function get_option( $post_id ) {
        $options_test = get_post_meta( $post_id, $this->post_name, true );
        if( empty($options_test) ) {
            $this->post_name = 'BeRocket_product_new_filter';
        }
        $options = parent::get_option( $post_id );
        if( empty($options_test) ) {
            $this->post_name = 'br_product_filter';
            update_post_meta( $post_id, $this->post_name, $options );
        }
        return $options;
    }
    public function wc_save_product_without_check( $post_id, $post ) {
        parent::wc_save_product_without_check( $post_id, $post );
        delete_site_transient('BeRocket_products_label_style_generate');
        $instance = $_POST[$this->post_name];
        
        $filter_type_array = array(
            'attribute' => array(
                'name' => __('Attribute', 'BeRocket_AJAX_domain'),
                'sameas' => 'attribute',
            ),
            'tag' => array(
                'name' => __('Tag', 'BeRocket_AJAX_domain'),
                'sameas' => 'tag',
            ),
            'all_product_cat' => array(
                'name' => __('Product Category', 'BeRocket_AJAX_domain'),
                'sameas' => 'custom_taxonomy',
                'attribute' => 'product_cat',
            ),
        );
        if ( function_exists('wc_get_product_visibility_term_ids') ) {
            $filter_type_array['_rating'] = array(
                'name' => __('Rating', 'BeRocket_AJAX_domain'),
                'sameas' => '_rating',
            );
        }
        $filter_type_array = apply_filters('berocket_filter_filter_type_array', $filter_type_array, $instance);
        if( ! array_key_exists($instance['filter_type'], $filter_type_array) ) {
            foreach($filter_type_array as $filter_type_key => $filter_type_val) {
                $instance['filter_type'] = $filter_type_key;
                break;
            }
        }
        if( ! empty($instance['filter_type']) && ! empty($filter_type_array[$instance['filter_type']]) && ! empty($filter_type_array[$instance['filter_type']]['sameas']) ) {
            $sameas = $filter_type_array[$instance['filter_type']];
            $instance['filter_type'] = $sameas['sameas'];
            if( ! empty($sameas['attribute']) ) {
                if( $sameas['sameas'] == 'custom_taxonomy' ) {
                    $instance['custom_taxonomy'] = $sameas['attribute'];
                } elseif( $sameas['sameas'] == 'attribute' ) {
                    $instance['attribute'] = $sameas['attribute'];
                }
            }
        }
        if( ! empty($_POST['br_widget_color']) and in_array($instance['filter_type'], apply_filters('berocket_filter_br_widget_color_types', array('attribute', 'custom_taxonomy', 'tag', 'product_cat'))) ) {
            $instance['use_value_with_color'] = ! empty($instance['use_value_with_color']);
            if( ( $attribute_temp = apply_filters('berocket_filter_br_widget_color_name', null, $instance, $post_id, $post) ) !== null ) {
                $_POST['tax_color_name']          = $attribute_temp;
            } elseif( $instance['filter_type'] == 'tag' ) {
                $_POST['tax_color_name']          = 'product_tag';
            } elseif( $instance['filter_type'] == 'product_cat' ) {
                $_POST['tax_color_name']          = 'product_cat';
            } else {
                $_POST['tax_color_name']          = $instance['attribute'];
            }
            if( ! empty($instance['style']) ) {
                $style = $instance['style'];
                $all_styles = get_option('BeRocket_AAPF_getall_Template_Styles');
                if( is_array($all_styles) && isset($all_styles[$style]) && ! empty($all_styles[$style]['specific']) ) {
                    BeRocket_AAPF_Widget_functions::color_image_save($instance, $all_styles[$style]['specific'], $_POST['br_widget_color']);
                }
            } elseif( ! empty($instance['type']) && in_array($instance['type'], array('color', 'image')) ) {
                BeRocket_AAPF_Widget_functions::color_image_save($instance, $_POST['br_widget_color'], $_POST['br_widget_color']);
            }
        }
        $setup_wizard = get_option('berocket_aapf_filters_setup_wizard_list');
        if( ! is_array($setup_wizard) ) {
            $setup_wizard = array();
        }
        $single_filter_wizard = br_get_value_from_array($setup_wizard, 'single_filter');
        if( $single_filter_wizard == -1 ) {
            $setup_wizard['single_filter'] = 1;
            update_option('berocket_aapf_filters_setup_wizard_list', $setup_wizard);
            if(!session_id()) {
                session_start();
            }
            $_SESSION['braapf_widget_wizard'] = true;
        }
    }
}


class BeRocket_AAPF_group_filters extends BeRocket_custom_post_class {
    public $hook_name = 'berocket_aapf_group_filters';
    public $conditions;
    protected static $instance;
    public $post_type_parameters = array(
        'can_be_disabled' => true
    );
    function __construct() {
        add_action('ajax_filters_framework_construct', array($this, 'init_conditions'));
        $this->post_name = 'br_filters_group';
        $this->post_settings = array(
            'label' => __( 'Product Filter Group', 'BeRocket_AJAX_domain' ),
            'labels' => array(
                'name'               => __( 'Product Filter Group', 'BeRocket_AJAX_domain' ),
                'singular_name'      => __( 'Product Filter Group', 'BeRocket_AJAX_domain' ),
                'menu_name'          => _x( 'Groups', 'Admin menu name', 'BeRocket_AJAX_domain' ),
                'add_new'            => __( 'Add Filter Group', 'BeRocket_AJAX_domain' ),
                'add_new_item'       => __( 'Add New Filter Group', 'BeRocket_AJAX_domain' ),
                'edit'               => __( 'Edit', 'BeRocket_AJAX_domain' ),
                'edit_item'          => __( 'Edit Filter Group', 'BeRocket_AJAX_domain' ),
                'new_item'           => __( 'New Filter Group', 'BeRocket_AJAX_domain' ),
                'view'               => __( 'View Filter Groups', 'BeRocket_AJAX_domain' ),
                'view_item'          => __( 'View Filter Group', 'BeRocket_AJAX_domain' ),
                'search_items'       => __( 'Search Product Filter Groups', 'BeRocket_AJAX_domain' ),
                'not_found'          => __( 'No Product Filter Groups found', 'BeRocket_AJAX_domain' ),
                'not_found_in_trash' => __( 'No Product Filter Groups found in trash', 'BeRocket_AJAX_domain' ),
            ),
                'description'     => __( 'This is where you can add Product Filter Groups.', 'BeRocket_AJAX_domain' ),
            'public'          => true,
            'show_ui'         => true,
            'capability_type' => 'group_filters',
            'map_meta_cap'    => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_in_menu'        => 'berocket_account',
            'hierarchical'        => false,
            'rewrite'             => false,
            'query_var'           => false,
            'supports'            => array( 'title' ),
            'show_in_nav_menus'   => false,
        );
        $this->default_settings = array(
            'data'                      => array(),
            'filters'                   => array(),
            'search_box_link_type'      => 'shop_page',
            'search_box_url'            => '',
            'search_box_category'       => '',
            'search_box_style'          => array(
                'position'                   => 'vertical',
                'search_position'            => 'after',
                'search_text'                => 'Search',
                'background'                 => 'bbbbff',
                'back_opacity'               => '0',
                'button_background'          => '888800',
                'button_background_over'     => 'aaaa00',
                'text_color'                 => '000000',
                'text_color_over'            => '000000',
            ),
        );
        $this->add_meta_box('conditions', __( 'Conditions', 'BeRocket_AJAX_domain' ));
        $this->add_meta_box('settings', __( 'Group Settings', 'BeRocket_AJAX_domain' ));
        $this->add_meta_box('meta_box_shortcode', __( 'Shortcode', 'BeRocket_AJAX_domain' ), false, 'side');
        $this->add_meta_box('information_faq', __( 'Information', 'BeRocket_AJAX_domain' ), false, 'side');
        parent::__construct();
        add_shortcode( 'br_filters_group', array( $this, 'shortcode' ) );
    }
    public function init_conditions() {
        $this->conditions = new BeRocket_conditions_AAPF($this->post_name.'[data]', $this->hook_name, array(
            'condition_page',
            'condition_page_woo_category'
        ));
    }
    public function conditions($post) {
        $options = $this->get_option( $post->ID );
        echo $this->conditions->build($options['data']);
        ?>
        <div class="section_conditions_hide_this_on">
            <table>
                <tr>
                    <th><?php _e('Hide this group on:', 'BeRocket_AJAX_domain'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" value="1" name="<?php echo $this->post_name; ?>[hide_group][mobile]"<?php if( ! empty($options['hide_group']['mobile']) ) echo ' checked'; ?>>
                            <?php _e('Mobile', 'BeRocket_AJAX_domain'); ?>
                        </label>
                        <label>
                            <input type="checkbox" value="1" name="<?php echo $this->post_name; ?>[hide_group][tablet]"<?php if( ! empty($options['hide_group']['tablet']) ) echo ' checked'; ?>>
                            <?php _e('Tablet', 'BeRocket_AJAX_domain'); ?>
                        </label>
                        <label>
                            <input type="checkbox" value="1" name="<?php echo $this->post_name; ?>[hide_group][desktop]"<?php if( ! empty($options['hide_group']['desktop']) ) echo ' checked'; ?>>
                            <?php _e('Desktop', 'BeRocket_AJAX_domain'); ?>
                        </label>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    public function settings($post) {
        wp_enqueue_script('jquery-ui-sortable');
        $filters = $this->get_option($post->ID);
        $post_name = $this->post_name;
        include AAPF_TEMPLATE_PATH . "filters_group.php";
    }
    public function information_faq($post) {
        include AAPF_TEMPLATE_PATH . "groups_information.php";
    }
    public function meta_box_shortcode($post) {global $pagenow;
        if( in_array( $pagenow, array( 'post-new.php' ) ) ) {
            _e( 'You need save it to get shortcode', 'BeRocket_AJAX_domain' );
        } else {
            echo "[br_filters_group group_id={$post->ID}]";
        }
    }
    public function manage_edit_columns ( $columns ) {
        $columns = parent::manage_edit_columns($columns);
        $columns["filters"] = __( "Filters", 'BeRocket_AJAX_domain' );
        $columns["shortcode"] = __( "Shortcode", 'BeRocket_AJAX_domain' );
        return $columns;
    }
    public function columns_replace ( $column ) {
        parent::columns_replace($column);
        global $post;
        $filters = $this->get_option($post->ID);
        switch ( $column ) {
            case "filters":
                $filter_links = '';
                if( isset($filters['filters']) && is_array($filters['filters']) ) {
                    foreach($filters['filters'] as $filter) {
                        $filter_id = $filter;
                        $filter_post = get_post($filter_id);
                        if( ! empty($filter_post) ) {
                            if( ! empty($filter_links) ) {
                                $filter_links .= ', ';
                            }
                            $filter_links .= '<a class="berocket_edit_filter" target="_blank" href="' . admin_url('post.php?post='.$filter_id.'&action=edit') . '">' . $filter_post->post_title . '</a>';
                        }
                    }
                }
                echo $filter_links;
                break;
            case "shortcode":
                echo "[br_filters_group group_id={$post->ID}]";
                break;
            default:
                break;
        }
    }
    public function shortcode( $atts = array() ) {
        if ( BeRocket_new_AAPF_Widget::check_widget_by_instance( $atts ) ) {
            ob_start();

            $BeRocket_AAPF_group_filters = BeRocket_AAPF_group_filters::getInstance();
            $group_options               = $BeRocket_AAPF_group_filters->get_option( $atts['group_id'] );

            the_widget( 'BeRocket_new_AAPF_Widget', $atts);

            return ob_get_clean();
        }

        return '';
    }
    public function get_option( $post_id ) {
        $options_test = get_post_meta( $post_id, $this->post_name, true );
        if( empty($options_test) ) {
            $this->post_name = 'br_filter_group';
        }
        $options = parent::get_option( $post_id );
        if( empty($options_test) ) {
            $this->post_name = 'br_filters_group';
            update_post_meta( $post_id, $this->post_name, $options );
        }
        return $options;
    }
    public function wc_save_product_without_check( $post_id, $post ) {
        parent::wc_save_product_without_check( $post_id, $post );
        $above_products = ! empty($_POST['br_filter_group_show_above']);
        $options = BeRocket_AAPF::get_aapf_option();
        $elements_above_products = br_get_value_from_array($options, 'elements_above_products');
        if( ! is_array($elements_above_products) ) {
            $elements_above_products = array();
        }
        $search_i = array_search($post_id, $elements_above_products);
        if( $search_i !== FALSE && ! $above_products ) {
            unset($elements_above_products[$search_i]);
        } elseif( $search_i === FALSE && $above_products ) {
            $elements_above_products[] = $post_id;
        }
        $options['elements_above_products'] = $elements_above_products;
        update_option('br_filters_options', $options);
    }
}

