<?php
class BeRocket_new_AAPF_Widget extends WP_Widget 
{
    public function __construct() {
        parent::__construct("berocket_aapf_group", __("AAPF Filters Group", 'BeRocket_AJAX_domain'),
            array("description" => __("AJAX Product Filters. Group of filters", 'BeRocket_AJAX_domain')));
    }
    public function widget($args, $instance) {
        if( ! self::check_widget_by_instance($instance) ) {
            return false;
        }
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['group_id'] = apply_filters( 'wpml_object_id', $instance['group_id'], 'page', true, $current_language );
        $BeRocket_AAPF_group_filters = BeRocket_AAPF_group_filters::getInstance();
        $filters = $BeRocket_AAPF_group_filters->get_option($instance['group_id']);
        $filters['group_id'] = $instance['group_id'];
        global $wp_registered_sidebars;
        $is_shortcode = empty($args['id']) || ! isset($wp_registered_sidebars[$args['id']]);
        $new_args = $args;
        if( ! $is_shortcode ) {
            $sidebar = $wp_registered_sidebars[$args['id']];
            $new_args = array_merge($new_args, $sidebar);
            $before_widget = $new_args['before_widget'];
        }
        $new_args['custom_class'] = trim(br_get_value_from_array($filters, 'custom_class'));
        $i = 1;
        ob_start();
        $custom_vars = array();
        $custom_vars = apply_filters('berocket_aapf_group_before_all', $custom_vars, $filters);
        $new_args = apply_filters('berocket_aapf_group_new_args', $new_args, $filters, $custom_vars);
        foreach($filters['filters'] as $filter) {
            $new_args_filter = apply_filters('berocket_aapf_group_new_args_filter', $new_args, $filters, $filter, $custom_vars);
            if( $is_shortcode ) {
                if( isset($new_args_filter['before_widget']) ) {
                    unset($new_args_filter['before_widget']);
                }
                if( isset($new_args_filter['after_widget']) ) {
                    unset($new_args_filter['after_widget']);
                }
            } else {
                $new_args_filter['widget_id'] = $args['widget_id'].'-'.$i;
                $new_args_filter['before_widget'] = sprintf($before_widget, $new_args_filter['widget_id'], '%s');
            }
            $custom_vars = apply_filters('berocket_aapf_group_before_filter', $custom_vars, $filters);
            the_widget( 'BeRocket_new_AAPF_Widget_single', array('filter_id' => $filter), $new_args_filter);
            $custom_vars = apply_filters('berocket_aapf_group_after_filter', $custom_vars, $filters);
            $i++;
        }
        $custom_vars = apply_filters('berocket_aapf_group_after_all', $custom_vars, $filters);
        $widget_html = ob_get_clean();
        if( ! empty($widget_html) ) {
            if( ! empty($instance['title']) ) {
                if( empty($new_args['title_class']) || ! is_array($new_args['title_class']) || count($new_args['title_class']) == 0 ) {
                    $new_args['title_class'] = array();
                }
                $new_args['title_class'][] = 'berocket_ajax_group_filter_title';
                echo '<h3 class="'.implode(' ', $new_args['title_class']).'">' . $instance['title'] . '</h3>';
            }
            braapf_is_filters_displayed_debug($instance['group_id'], 'group', 'displayed', 'Must be displayed on the page');
            echo $widget_html;
        } else {
            braapf_is_filters_displayed_debug($instance['group_id'], 'group', 'empty_filter_code', 'Filters inside do not return any HTML code');
            return false;
        }
    }
    public static function check_widget_by_instance($instance) {
        if( empty($instance['group_id']) || get_post_status($instance['group_id']) != 'publish' ) {
            return false;
        }
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $br_options = $BeRocket_AAPF->get_option();
        if( ! empty($br_options['filters_turn_off']) ) {
            return false;
        }
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['group_id'] = apply_filters( 'wpml_object_id', $instance['group_id'], 'page', true, $current_language );
        $BeRocket_AAPF_group_filters = BeRocket_AAPF_group_filters::getInstance();
        $filters = $BeRocket_AAPF_group_filters->get_option($instance['group_id']);
        global $braapf_parameters;
        if( $braapf_parameters['do_not_display_filters'] ) {
            braapf_is_filters_displayed_debug($instance['group_id'], 'group', 'disabled', 'Custom parameter do_not_display_filters');
            return false;
        }
        if( empty($filters) ) {
            braapf_is_filters_displayed_debug($instance['group_id'], 'group', 'empty_options', 'Options data from database empty');
            return false;
        }
        if( has_term('isdisabled', 'berocket_taxonomy_data', intval($instance['group_id'])) ) {
            braapf_is_filters_displayed_debug($instance['group_id'], 'group', 'disabled', 'Disabled by user');
            return false;
        }
        if( empty($filters['filters']) || ! is_array($filters['filters']) || ! count($filters['filters']) ) {
            braapf_is_filters_displayed_debug($instance['group_id'], 'group', 'without_filters', 'Do not have any filters');
            return false;
        }
        if( apply_filters('braapf_check_widget_by_instance_group', (! empty($filters['data']) && ! BeRocket_conditions::check($filters['data'], $BeRocket_AAPF_group_filters->hook_name) ), $instance, $filters ) ) {
            braapf_is_filters_displayed_debug($instance['group_id'], 'group', 'condition_restriction', 'Disabled for this page by conditions');
            return false;
        }
        return true;
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['group_id'] = strip_tags( @ $new_instance['group_id'] );
        $instance['title'] = strip_tags( @ $new_instance['title'] );
        return $instance;
    }
    public function form($instance) {
        wp_enqueue_script( 'berocket_aapf_widget-admin' );
        wp_enqueue_script('jquery-color');
        $instance = wp_parse_args( (array) $instance, array( 'group_id' => '', 'title' => '') );
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['group_id'] = apply_filters( 'wpml_object_id', $instance['group_id'], 'page', true, $current_language );
        echo '<a href="' . admin_url('edit.php?post_type=br_filters_group') . '">' . __('Manage groups', 'BeRocket_AJAX_domain') . '</a>';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('group_id'); ?>"><?php _e('Group', 'BeRocket_AJAX_domain'); ?></label><br>
            <?php
            $query = new WP_Query(array('post_type' => 'br_filters_group', 'nopaging' => true, 'fields' => 'ids'));
            $posts = $query->get_posts();
            $edit_link_current = '';
            if ( !empty($posts) ) {
                echo '<select class="berocket_new_widget_selectbox group" id="'.$this->get_field_id('group_id').'" name="'.$this->get_field_name('group_id').'">';
                echo '<option>'.__('--Please select group--', 'BeRocket_AJAX_domain').'</option>';
                foreach($posts as $post_id) {
                    if( empty($instance['group_id']) ) {
                        $instance['group_id'] = $post_id;
                    }
                    echo '<option data-edit="'.get_edit_post_link($post_id).'" value="' . $post_id . '"'.($post_id == $instance['group_id'] ? ' selected' : '').'>' . substr(get_the_title($post_id), 0, 50) . (strlen(get_the_title($post_id)) > 50 ? '...' : '') . ' (ID:' . $post_id . ')</option>';
                    if( $post_id == $instance['group_id'] ) {
                        $edit_link_current = get_edit_post_link($post_id);
                    }
                }
                echo '</select>';
            }
            ?>
            <a target="_blank" class="berocket_aapf_edit_post_link" href="<?php echo $edit_link_current; ?>"<?php if( empty($edit_link_current) ) echo ' style="display: none;"'; ?>><?php _e('Edit', 'BeRocket_AJAX_domain'); ?></a>
        </p>
        <?php
    }
}
class BeRocket_new_AAPF_Widget_single extends WP_Widget 
{
    public function __construct() {
        parent::__construct("berocket_aapf_single", __("AAPF Filter Single", 'BeRocket_AJAX_domain'),
            array("description" => __("AJAX Product Filters. Single Filter", 'BeRocket_AJAX_domain')));
    }
    public function widget($args, $instance) {
        global $bapf_unique_id;
        $bapf_unique_id++;
        if( ! self::check_widget_by_instance($instance) ) {
            return true;
        }
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['filter_id'] = apply_filters( 'wpml_object_id', $instance['filter_id'], 'page', true, $current_language );
        $filter_id = $instance['filter_id'];
        $filter_post = get_post($filter_id);
        $BeRocket_AAPF_single_filter = BeRocket_AAPF_single_filter::getInstance();
        $filter_data = $BeRocket_AAPF_single_filter->get_option($filter_id);
        if( empty($filter_data) || ! is_array($filter_data) ) {
            $filter_data = array();
        }
        if( ! empty($args['filter_data']) && is_array($args['filter_data']) ) {
            if( ! empty($filter_data['widget_collapse']) ) {
                $args['filter_data']['widget_collapse'] = $filter_data['widget_collapse'];
            }
            $filter_data = array_merge($filter_data, $args['filter_data']);
        }
        if ( empty($instance['br_wp_footer']) ) {
            global $br_widget_ids;
            if ( ! isset( $br_widget_ids ) ) {
                $br_widget_ids = array();
            }
            $instance['is_new_widget'] = true;
            $br_widget_ids[] = array('instance' => $instance, 'args' => $args);
        }
        $filter_data['br_wp_footer'] = true;
        $filter_data['show_page'] = array();
        $filter_data['title'] = $filter_post->post_title;
        $additional_class = br_get_value_from_array($args, 'additional_class');
        if( ! is_array($additional_class) ) {
            $additional_class = array();
        }
        if( ! empty($filter_data['is_hide_mobile']) ) {
            $additional_class[] = 'bapf_sngl_hd_mobile';
        }
        if( ! empty($filter_data['hide_group']['tablet']) ) {
            $additional_class[] = 'bapf_sngl_hd_tablet';
        }
        if( ! empty($filter_data['hide_group']['desktop']) ) {
            $additional_class[] = 'bapf_sngl_hd_desktop';
        }
        if( ! empty($filter_data['reset_hide']) && $filter_data['widget_type'] == 'reset_button' ) {
            $additional_class[] = $filter_data['reset_hide'];
        }
        $additional_class[] = 'berocket_single_filter_widget';
        $additional_class[] = 'berocket_single_filter_widget_' . esc_html($filter_id);
        $additional_class[] = trim(br_get_value_from_array($args, 'custom_class'));
        $filter_data['filter_id'] = $filter_id;
        ob_start();
        new BeRocket_AAPF_Widget($filter_data, $args);
        $element_displayed = trim(ob_get_clean());
        if( empty($element_displayed) ) {
            braapf_is_filters_displayed_debug($filter_id, 'filter', 'displayed_empty', 'Must be displayed, but empty');
        }
        if( ! apply_filters('BeRocket_AAPF_widget_old_display_conditions', true, $filter_data, $instance, $args) ) {
            $element_displayed = '';
        }
        if( empty($element_displayed) ) {
            $additional_class[] = 'bapf_mt_none';
        } else {
            braapf_is_filters_displayed_debug($filter_id, 'filter', 'displayed', 'Must be displayed on the page');
        }
        $additional_class = apply_filters('BeRocket_AAPF_widget_additional_classes', $additional_class, $filter_id, $filter_data);
        $additional_class = array_unique($additional_class);
        if( ! empty($filter_data['widget_type']) && ($filter_data['widget_type'] == 'update_button' || $filter_data['widget_type'] == 'reset_button' ) ) {
            $search_berocket_hidden_clickable = array_search('berocket_hidden_clickable', $additional_class);
            if( $search_berocket_hidden_clickable !== FALSE ) {
                unset($additional_class[$search_berocket_hidden_clickable]);
            }
            $additional_class_esc = implode(' ', $additional_class);
            $additional_class_esc = esc_html($additional_class_esc);
            echo '<div class="' . $additional_class_esc . '" data-id="' . esc_html($filter_id) . '" style="'.htmlentities(br_get_value_from_array($args, 'inline_style')).'"'.htmlentities(br_get_value_from_array($args, 'additional_data_inline')).'>';
        } else {
            $additional_class_esc = implode(' ', $additional_class);
            $additional_class_esc = esc_html($additional_class_esc);
            echo '<div class="' . $additional_class_esc . '" data-id="' . esc_html($filter_id) . '" style="'.htmlentities(br_get_value_from_array($args, 'inline_style')).'"'.htmlentities(br_get_value_from_array($args, 'additional_data_inline')).'>';
            if( ! empty($args['widget_inline_style']) ) {
                $classes_arr = trim($additional_class_esc);
                $classes_arr = explode(' ', preg_replace('!\s+!', ' ', $classes_arr));
                $classes_arr = '.' . implode('.', $classes_arr);
                $classes_arr .= ' .bapf_body';
                $classes_arr = esc_html($classes_arr);
                echo '<style>';
                echo $classes_arr;
                echo '{' . esc_html($args['widget_inline_style']) . '}';
                echo '</style>';
            }
        }
        echo $element_displayed;
        echo '</div>';
    }
    public static function check_widget_by_instance($instance) {
        if( empty($instance['filter_id']) || get_post_status($instance['filter_id']) != 'publish' ) {
            if( empty($instance['filter_id']) ) {
                braapf_is_filters_displayed_debug('000', 'filter', 'empty_ID', 'Some filter has empty ID');
            } else {
                braapf_is_filters_displayed_debug($instance['filter_id'], 'filter', 'not_published', 'Filter not published');
            }
            return false;
        }
        $BeRocket_AAPF = BeRocket_AAPF::getInstance();
        $br_options = $BeRocket_AAPF->get_option();
        if( ! empty($br_options['filters_turn_off']) ) {
            braapf_is_filters_displayed_debug($instance['filter_id'], 'filter', 'disabled', 'Disabled by user in global settings');
            return false;
        }
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['filter_id'] = apply_filters( 'wpml_object_id', $instance['filter_id'], 'page', true, $current_language );
        $filter_id = $instance['filter_id'];
        $filter_post = get_post($filter_id);
        $BeRocket_AAPF_single_filter = BeRocket_AAPF_single_filter::getInstance();
        $filter_data = $BeRocket_AAPF_single_filter->get_option($filter_id);
        global $braapf_parameters;
        if( $braapf_parameters['do_not_display_filters'] ) {
            braapf_is_filters_displayed_debug($instance['filter_id'], 'filter', 'disabled', 'Custom parameter do_not_display_filters');
            return false;
        }
        if( has_term('isdisabled', 'berocket_taxonomy_data', intval($instance['filter_id'])) ) {
            braapf_is_filters_displayed_debug($instance['filter_id'], 'filter', 'disabled', 'Disabled by user');
            return false;
        }
        if( empty($filter_data) || empty($filter_post) ) {
            return false;
        }
        if( apply_filters('braapf_check_widget_by_instance_single', (! empty($filter_data['data']) && ! BeRocket_conditions::check($filter_data['data'], $BeRocket_AAPF_single_filter->hook_name) ), $instance, $filter_data ) ) {
            braapf_is_filters_displayed_debug($instance['filter_id'], 'filter', 'condition_restriction', 'Disabled for this page by conditions');
            return false;
        }
        return true;
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['filter_id'] = strip_tags( $new_instance['filter_id'] );
        $instance['filter_id'] = intval($instance['filter_id']);
        return $instance;
    }
    public function form($instance) {
        wp_enqueue_script( 'berocket_aapf_widget-admin' );
        $instance = wp_parse_args( (array) $instance, array( 'filter_id' => '') );
        $current_language = apply_filters( 'wpml_current_language', NULL );
        $instance['filter_id'] = apply_filters( 'wpml_object_id', $instance['filter_id'], 'page', true, $current_language );
        echo '<a href="' . admin_url('edit.php?post_type=br_product_filter') . '">' . __('Manage filters', 'BeRocket_AJAX_domain') . '</a>';
        ?>
        <p class="berocketwizard_aapf_single_widget_filter_id">
            <label for="<?php echo $this->get_field_id('filter_id'); ?>"><?php _e('Filter', 'BeRocket_AJAX_domain'); ?></label><br>
            <?php
            $query = new WP_Query(array('post_type' => 'br_product_filter', 'nopaging' => true, 'fields' => 'ids'));
            $posts = $query->get_posts();
            $edit_link_current = '';
            if ( !empty($posts) ) {
                echo '<select class="berocket_new_widget_selectbox single" id="'.$this->get_field_id('filter_id').'" name="'.$this->get_field_name('filter_id').'">';
                echo '<option>'.__('--Please select filter--', 'BeRocket_AJAX_domain').'</option>';
                foreach($posts as $post_id) {
                    if( empty($instance['filter_id']) ) {
                        $instance['filter_id'] = $post_id;
                    }
                    echo '<option data-edit="'.get_edit_post_link($post_id).'" value="' . $post_id . '"'.($post_id == $instance['filter_id'] ? ' selected' : '').'>' . substr(get_the_title($post_id), 0, 50) . (strlen(get_the_title($post_id)) > 50 ? '...' : '') . ' (ID:' . $post_id . ')</option>';
                    if( $post_id == $instance['filter_id'] ) {
                        $edit_link_current = get_edit_post_link($post_id);
                    }
                }
                echo '</select>';
            }
            ?>
            <a target="_blank" class="berocket_aapf_edit_post_link" href="<?php echo $edit_link_current; ?>"<?php if( empty($edit_link_current) ) echo ' style="display: none;"'; ?>><?php _e('Edit', 'BeRocket_AJAX_domain'); ?></a>
        </p>
        <?php
    }
}
?>
