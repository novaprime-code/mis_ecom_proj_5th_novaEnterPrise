<?php
if( ! class_exists('braapf_single_filter_edit_elements') ) {
    class braapf_single_filter_edit_elements {
        function __construct() {
            //Attribute setup elements
            add_action('braapf_single_filter_attribute_setup', array(__CLASS__, 'filter_type'), 100, 2);
            add_action('braapf_single_filter_attribute_setup', array(__CLASS__, 'order_values'), 200, 2);
            add_action('braapf_advanced_single_filter_attribute_setup', array(__CLASS__, 'cat_value_limit'), 400, 2);
            //STYLES
            add_action('braapf_single_filter_style', array(__CLASS__, 'styles_template'), 100, 2);
            //REQUIRED
            add_action('braapf_single_filter_required', array(__CLASS__, 'color_image_pick'), 100, 2);
            //ADDITIONAL
            //FOR ALL FILTERS
            add_action('braapf_single_filter_additional', array(__CLASS__, 'hierarhical_sort'), 50, 2);
            add_action('braapf_single_filter_additional', array(__CLASS__, 'collapse_option'), 100, 2);
            add_action('braapf_single_filter_additional', array(__CLASS__, 'single_selection'), 200, 2);
            add_action('braapf_single_filter_additional', array(__CLASS__, 'attribute_count'), 300, 2);
            //advanced
            add_action('braapf_advanced_single_filter_additional', array(__CLASS__, 'description'), 100, 2);
            add_action('braapf_advanced_single_filter_additional', array(__CLASS__, 'css_class'), 200, 2);
            add_action('braapf_advanced_single_filter_additional', array(__CLASS__, 'filter_height_scroll'), 300, 2);
            add_action('braapf_advanced_single_filter_additional', array(__CLASS__, 'icons'), 1000, 2);
            //COLOR/IMAGE
            add_action('braapf_single_filter_additional', array(__CLASS__, 'display_name_with_color_image'), 400, 2);
            add_action('braapf_advanced_single_filter_additional', array(__CLASS__, 'selection_type'), 400, 2);
            //PRICE ATTRIBUTE
            add_action('braapf_single_filter_additional', array(__CLASS__, 'price_values'), 500, 2);
            add_action('braapf_single_filter_additional', array(__CLASS__, 'min_max_price_values'), 600, 2);
            add_action('braapf_single_filter_additional', array(__CLASS__, 'text_before_after_price'), 700, 2);
            add_action('braapf_single_filter_additional', array(__CLASS__, 'specific_number_styles'), 800, 2);
            //SELECTED FILTERS AREA
            add_action('braapf_single_filter_additional', array(__CLASS__, 'selected_filters_area'), 800, 2);
            //RESET BUTTON
            add_action('braapf_single_filter_additional', array(__CLASS__, 'reset_button_hide'), 500, 2);
            //SAVE SETTING
            add_action('braapf_single_filter_save', array(__CLASS__, 'save_button'), 5000, 2);
        }
        //Attribute setup elements
        static function filter_type($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex braapf_filter_type_data">';
                //FILTER TYPE
                $filter_type_array = self::get_all_filter_type_array($braapf_filter_settings);
                $filter_type = br_get_value_from_array($braapf_filter_settings, 'filter_type', 'price');
                echo '<div class="braapf_filter_type braapf_half_select_full">';
                    echo '<label for="braapf_filter_type">' . __('Filter By', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_filter_type" name="'.$settings_name.'[filter_type]">';
                    foreach($filter_type_array as $filter_type_key => $filter_type_val) {
                        echo '<option';
                        foreach($filter_type_val as $data_key => $data_val) {
                            if( ! empty($data_val) ) {
                                echo ' data-'.$data_key."='".(is_array($data_val) ? json_encode($data_val) : $data_val)."'";
                            }
                        }
                        echo ' value="'.$filter_type_key.'"'.($filter_type == $filter_type_key ? ' selected' : '').'>'.$filter_type_val['name'].'</option>';
                    }
                    echo '</select>';
                echo '</div>';
                //ATTRIBUTE
                $attributes_list = br_aapf_get_attributes();
                foreach($attributes_list as $attr_taxonomy_name => &$attribute_list) {
                    $attr_taxonomy = get_taxonomy($attr_taxonomy_name);
                    $attribute_list = array(
                        'name' => $attribute_list,
                        'hierarchical' => empty($attr_taxonomy->hierarchical) ? 0 : 1
                    );
                }
                if( isset($attribute_list) ) {
                    unset($attribute_list);
                }
                $attribute = br_get_value_from_array($braapf_filter_settings, 'attribute', '');
                echo '<div class="braapf_attribute braapf_half_select_full">';
                    echo '<label for="braapf_attribute">' . __('Attribute', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_attribute" name="'.$settings_name.'[attribute]">';
                    foreach ( $attributes_list as $value => $data ) {
                        echo '<option';
                        foreach($data as $data_key => $data_val) {
                            if( $data_val !== "" ) {
                                echo ' data-'.$data_key.'="'.$data_val.'"';
                            }
                        }
                        echo ( $attribute == $value ? ' selected' : '' ) . ' value="' . $value . '">' . $data['name'] . '</option>';
                    }
                    echo '</select>';
                echo '</div>';
                do_action('braapf_single_filter_filter_type', $settings_name, $braapf_filter_settings);
                //CUSTOM TAXONOMY
                $custom_taxonomies_list = self::get_custom_taxonomies();
                $custom_taxonomy = br_get_value_from_array($braapf_filter_settings, 'custom_taxonomy', '');
                echo '<div class="braapf_custom_taxonomy braapf_half_select_full">';
                    echo '<label for="braapf_custom_taxonomy">' . __('Custom Taxonomies', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_custom_taxonomy" name="'.$settings_name.'[custom_taxonomy]">';
                    foreach ( $custom_taxonomies_list as $value => $data ) {
                        echo '<option';
                        foreach($data as $data_key => $data_val) {
                            if( $data_val !== "" ) {
                                echo ' data-'.$data_key.'="'.$data_val.'"';
                            }
                        }
                        echo ( $custom_taxonomy == $value ? ' selected' : '' ) . ' value="' . $value . '">' . $data['name'] . '</option>';
                    }
                    echo '</select>';
                echo '</div>';
            echo '</div>';
        }
        static function order_values($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                //ORDER BY
                $sorting_types = array(
                    array(
                        'value' => '',
                        'name'  => __('Default', 'BeRocket_AJAX_domain')
                    ),
                    array(
                        'value' => 'Alpha',
                        'name'  => __('Alpha', 'BeRocket_AJAX_domain')
                    ),
                    array(
                        'value' => 'Numeric',
                        'name'  => __('Numeric', 'BeRocket_AJAX_domain')
                    ),
                );
                $order_values_by = br_get_value_from_array($braapf_filter_settings, 'order_values_by', '');
                echo '<div class="braapf_order_values_by braapf_half_select_full">';
                    echo '<label for="braapf_order_values_by">' . __('Values Order', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_order_values_by" name="'.$settings_name.'[order_values_by]">';
                    foreach($sorting_types as $sorting_type) {
                        echo '<option value="'.$sorting_type['value'].'"'.($order_values_by == $sorting_type['value'] ? ' selected' : '').'>'.$sorting_type['name'].'</option>';
                    }
                    echo '</select>';
                echo '</div>';
                //ORDER TYPE
                $sorting_types = array(
                    array(
                        'value' => 'asc',
                        'name'  => __('Ascending', 'BeRocket_AJAX_domain')
                    ),
                    array(
                        'value' => 'desc',
                        'name'  => __('Descending', 'BeRocket_AJAX_domain')
                    ),
                );
                $order_values_type = br_get_value_from_array($braapf_filter_settings, 'order_values_type', '');
                echo '<div class="braapf_order_values_type braapf_half_select_full">';
                    echo '<label for="braapf_order_values_type">' . __('Order Direction', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_order_values_type" name="'.$settings_name.'[order_values_type]">';
                    foreach($sorting_types as $sorting_type) {
                        echo '<option value="'.$sorting_type['value'].'"'.($order_values_type == $sorting_type['value'] ? ' selected' : '').'>'.$sorting_type['name'].'</option>';
                    }
                    echo '</select>';
                echo '</div>';
            echo '</div>';
        }
        static function cat_value_limit($settings_name, $braapf_filter_settings) {
            $cat_value_limit = br_get_value_from_array($braapf_filter_settings, 'cat_value_limit', '0');
            if( apply_filters('braapf_single_filter_hide_cat_value_limit', empty($cat_value_limit), $cat_value_limit) ) {
                return;
            }
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_cat_value_limit braapf_full_select_full">';
                    $hrterms = berocket_aapf_get_terms(array(
                        'taxonomy'          => 'product_cat',
                        'hide_empty'        => false
                    ), array(
                        'disable_recount'   => true,
                        'hierarchical'      => true
                    ));
                    echo '<label for="braapf_cat_value_limit">' . __('Limit filter values by products from the selected category', 'BeRocket_AJAX_domain')
                    . '<span id="braapf_sinfo_cat_value_limit" class="dashicons dashicons-editor-help"></span>' . '</label>';
                    echo '<select id="braapf_cat_value_limit" name="'.$settings_name.'[cat_value_limit]">';
                        echo '<option value="">' . __('Use all attribute values', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<optgroup label="'.__('Limit by category:', 'BeRocket_AJAX_domain').'">';
                        foreach($hrterms as $hrterm) {
                            echo '<option value="'.urldecode($hrterm->slug).'"'.($cat_value_limit == urldecode($hrterm->slug) ? ' selected' : '').'>';
                            for( $i = 0; $i < $hrterm->depth; $i++ ) {
                                echo '- ';
                            }
                            echo $hrterm->name.'</option>';
                        }
                        echo '</optgroup>';
                    echo '</select>';
                echo '</div>';
            echo '</div>';
            $tooltip_text = '<strong>' . __('Option does not hide filters on pages.', 'BeRocket_AJAX_domain') . '</strong>'
            . '<p>' . __('Filter will be displayed on same pages, but values that is displayed in filter will be limited by products that is inside selected category.', 'BeRocket_AJAX_domain') . '</p>'
            . '<p>' . __('To limit pages where filters are displayed use "Conditions" meta box.', 'BeRocket_AJAX_domain') . '</p>';
            BeRocket_AAPF::add_tooltip('#braapf_sinfo_cat_value_limit', $tooltip_text);
        }
        //STYLES
        static function styles_template($settings_name, $braapf_filter_settings) {
            $styles = apply_filters('BeRocket_AAPF_getall_Template_Styles', array());
            $style_setting = br_get_value_from_array($braapf_filter_settings, 'style', '');
            $templates = array();
            foreach($styles as $style_id => $style_data) {
                $JQdata = '';
                if( empty($style_data['image']) || ! file_exists(plugin_dir_path($style_data['file']) . str_replace(plugin_dir_url($style_data['file']), '', $style_data['image'])) ) {
                    $style_data['image'] = plugin_dir_url( BeRocket_AJAX_filters_file ) . 'images/without-preview.png';
                }
                foreach($style_data as $data_name => $data_value) {
                    if( (is_string($data_value) || is_numeric($data_value)) && ! in_array($data_name, array('this', 'file', 'style_file', 'script_file')) ) {
                        $JQdata_ok = true;
                        if( in_array($data_name, array('image_price', 'image')) ) {
                            $JQdata_ok = false;
                            $path = plugin_dir_path($style_data['file']) . str_replace(plugin_dir_url($style_data['file']), '', $data_value);
                            if( file_exists($path) ) {
                                $JQdata_ok = true;
                            }
                        }
                        if($JQdata_ok) {
                            $JQdata .= ' data-'. $data_name.'="'.$data_value.'"';
                        }
                    }
                }
                if( ! isset($templates[$style_data['template'].'+'.$style_data['specific']]) ) {
                    $templates[$style_data['template'].'+'.$style_data['specific']] = array(
                        'template' => $style_data['template'],
                        'specific' => $style_data['specific'],
                        'html'     => array()
                    );
                }
                $style_html = '<div class="braapf_style_'.$style_id.'"'.$JQdata.'>';
                    $style_html .= '<input id="braapf_style_'.$style_id.'" type="radio" name="'.$settings_name.'[style]" value="'.$style_id.'"'.($style_setting == $style_id ? ' checked' : '') . $JQdata . '>';
                    $style_html .= '<label for="braapf_style_'.$style_id.'">';
                        $style_html .= '<img alt="'.$style_data['name'].'" src="'.$style_data['image'].'">';
                        $style_html .= '<h3>'.$style_data['name'].'</h3>';
                        $style_html .= '<span class="braapf_active"><i class="fa fa-check"></i></span>';
                    $style_html .= '</label>';
                $style_html .= '</div>';
                if( isset($style_data['sort_pos']) && $style_data['sort_pos'] == 1 ) {
                    $templates[$style_data['template'].'+'.$style_data['specific']]['html'] = 
                        array($style_id => $style_html) + $templates[$style_data['template'].'+'.$style_data['specific']]['html'];
                } else {
                    $templates[$style_data['template'].'+'.$style_data['specific']]['html'][$style_id] = $style_html;
                }
            }
            $templates_data = self::get_all_style_template_data();
            echo '<div class="braapf_templates_list">';
            foreach($templates as $template_slug => $template_data) {
                echo '<div class="braapf_template_'.$template_data['template'].'_'.$template_data['specific'].'">';
                    $template_name = br_get_value_from_array($templates_data, array($template_data['template'], (empty($template_data['specific']) ? 0 : $template_data['specific'])));
                    if( empty($template_name) ) {
                        $template_name = ucfirst(str_replace('_', ' ', $template_data['template']));
                        if( ! empty($template_data['specific']) ) {
                            $template_name .= '. ' .ucfirst(str_replace('_', ' ', $template_data['specific']));
                        }
                    }
                    echo '<h4>'.$template_name.'</h4>';
                    echo '<div class="braapf_style">';
                        $template_html = implode($template_data['html']);
                        echo $template_html;
                    echo '</div>';
                    echo '<script>jQuery(document).on("brsbs_style", function() {';
                        ?>berocket_show_element('.braapf_template_<?php echo $template_slug; ?>', '!braapf_current_template_styles! == "<?php echo $template_slug; ?>"', true, braapf_sort_styles);<?php
                    echo '});</script>';
                echo '</div>';
            }
            echo '</div>';
            echo '<script>jQuery(document).on("brsbs_style", function() {';
            foreach($templates as $template) {
                ?>berocket_show_element('.braapf_style > div[data-template="<?php echo $template['template']; ?>"][data-specific="<?php echo $template['specific']; ?>"]', '!braapf_current_template_styles! == "<?php echo $template['template']; ?>" && !braapf_current_specific_styles! == "<?php echo $template['specific']; ?>"', true, braapf_sort_styles);<?php
                ?>berocket_show_element('.braapf_template_<?php echo $template['template']; ?>_<?php echo $template['specific']; ?>', '!braapf_current_template_styles! == "<?php echo $template['template']; ?>" && !braapf_current_specific_styles! == "<?php echo $template['specific']; ?>"');<?php
            }
            echo '});</script>';
        }
        //REQUIRED
        static function color_image_pick($settings_name, $braapf_filter_settings) {
            $taxonomy_name = self::get_curent_taxonomy_name($braapf_filter_settings);
            $styles = apply_filters('BeRocket_AAPF_getall_Template_Styles', array());
            $style_setting = br_get_value_from_array($braapf_filter_settings, 'style', '');
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_widget_color_pick braapf_full_select_full">';
                    echo BeRocket_AAPF_Widget_functions::color_image_view( $braapf_filter_settings, br_get_value_from_array($styles, array($style_setting, 'specific'), ''), true);
                echo '</div>';
            echo '</div>';
        }
        //ADDITIONAL
        //FOR ALL FILTERS
        static function hierarhical_sort($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                $hide_child_attributes = br_get_value_from_array($braapf_filter_settings, 'hide_child_attributes', '');
                echo '<div class="braapf_hide_child_attributes braapf_full_select_full">';
                    echo '<label for="braapf_hide_child_attributes">' . __('Hierarchical', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_hide_child_attributes" name="'.$settings_name.'[hide_child_attributes]">';
                        echo '<option value=""'.($hide_child_attributes == "" ? ' selected' : '').'>' . __('Disabled', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="2"'.($hide_child_attributes == "2" ? ' selected' : '').'>' . __('Display hierarchical', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="1"'.($hide_child_attributes == "1" ? ' selected' : '').'>' . __('Display hierarchical and hide child', 'BeRocket_AJAX_domain') . '</option>';
                    echo '</select>';
                echo '</div>';
            echo '</div>';
        }
        static function collapse_option($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                $widget_collapse = br_get_value_from_array($braapf_filter_settings, 'widget_collapse', '');
                echo '<div class="braapf_widget_collapse braapf_half_select_full">';
                    echo '<label for="braapf_widget_collapse">' . __('Enable minimization option', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_widget_collapse" name="'.$settings_name.'[widget_collapse]">';
                        echo '<option value=""'.($widget_collapse == "" ? ' selected' : '').'>' . __('Disabled', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="with_arrow"'.($widget_collapse == "with_arrow" ? ' selected' : '').'>' . __('Enabled with arrow', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="without_arrow"'.($widget_collapse == "without_arrow" ? ' selected' : '').'>' . __('Enabled without arrow', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="without_arrow_mobile"'.($widget_collapse == "without_arrow_mobile" ? ' selected' : '').'>' . __('Enabled without arrow on mobile', 'BeRocket_AJAX_domain') . '</option>';
                    echo '</select>';
                echo '</div>';
                echo '<div class="braapf_widget_is_hide braapf_half_select_full">';
                    $widget_is_hide = br_get_value_from_array($braapf_filter_settings, 'widget_is_hide', '0');
                    echo '<p>';
                        echo '<input id="braapf_widget_is_hide" type="checkbox" name="' . $settings_name . '[widget_is_hide]"' . ( empty($widget_is_hide) ? '' : ' checked' ) . ' value="1">';
                        echo '<label for="braapf_widget_is_hide">'.__('Minimize the widget on load?', 'BeRocket_AJAX_domain').'</label>';
                    echo '</p>';
                echo '</div>';
            echo '</div>';
        }
        static function single_selection($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_single_selection braapf_half_select_full">';
                    $single_selection = br_get_value_from_array($braapf_filter_settings, 'single_selection', '0');
                    echo '<p>';
                        echo '<input id="braapf_single_selection" type="checkbox" name="' . $settings_name . '[single_selection]"' . ( empty($single_selection) ? '' : ' checked' ) . ' value="1">';
                        echo '<label for="braapf_single_selection">'.__('Single Selection. Only one value can be selected at a time', 'BeRocket_AJAX_domain').'</label>';
                    echo '</p>';
                echo '</div>';
                echo '<div class="braapf_operator braapf_half_select_full">';
                    $operator = br_get_value_from_array($braapf_filter_settings, 'operator', '');
                    echo '<label for="braapf_operator">' . __('Operator', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_operator" name="'.$settings_name.'[operator]">';
                        echo '<option value="OR"'.($operator == "OR" ? ' selected' : '').'>' . __('OR', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="AND"'.($operator == "AND" ? ' selected' : '').'>' . __('AND', 'BeRocket_AJAX_domain') . '</option>';
                    echo '</select>';
                echo '</div>';
                echo '<div class="braapf_select_first_element_text braapf_half_select_full">';
                    $select_first_element_text = br_get_value_from_array($braapf_filter_settings, 'select_first_element_text', '');
                    echo '<label class="braapf_select_first_element_text_for_single" for="braapf_select_first_element_text">'.__('Text of the first element', 'BeRocket_AJAX_domain').'</label>';
                    echo '<label class="braapf_select_first_element_text_for_multiple" for="braapf_select_first_element_text">'.__('Placeholder Text', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input id="braapf_select_first_element_text" type="text" name="' . $settings_name . '[select_first_element_text]" value="'.$select_first_element_text.'" placeholder="'.__('Any', 'BeRocket_AJAX_domain').'">';
                echo '</div>';
            echo '</div>';
        }
        static function attribute_count($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_attribute_count braapf_half_select_full">';
                    $attribute_count = br_get_value_from_array($braapf_filter_settings, 'attribute_count', '');
                    echo '<label for="braapf_attribute_count">'.__('Number of Attribute values', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input id="braapf_attribute_count" type="text" name="' . $settings_name . '[attribute_count]" value="'.$attribute_count.'" placeholder="'.__('From settings', 'BeRocket_AJAX_domain').'">';
                echo '</div>';
                echo '<div class="braapf_attribute_count_show_hide braapf_half_select_full">';
                    $attribute_count_show_hide = br_get_value_from_array($braapf_filter_settings, 'attribute_count_show_hide', '');
                    echo '<label for="braapf_attribute_count_show_hide">' . __('Show/Hide button', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_attribute_count_show_hide" name="'.$settings_name.'[attribute_count_show_hide]">';
                        echo '<option value=""'.($attribute_count_show_hide == "" ? ' selected' : '').'>' . __('Default', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="visible"'.($attribute_count_show_hide == "visible" ? ' selected' : '').'>' . __('Always visible', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="hidden"'.($attribute_count_show_hide == "hidden" ? ' selected' : '').'>' . __('Always hidden', 'BeRocket_AJAX_domain') . '</option>';
                    echo '</select>';
                echo '</div>';
            echo '</div>';
        }
        //advanced
        static function description($settings_name, $braapf_filter_settings) {
            $style_setting = br_get_value_from_array($braapf_filter_settings, 'style', '');
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_description braapf_full_select_full">';
                    $description = br_get_value_from_array($braapf_filter_settings, 'description', '');
                    echo '<label for="braapf_description">' . __('Description', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<textarea id="braapf_description" type="text" name="' . $settings_name . '[description]" placeholder="'.__('Description do not displayed', 'BeRocket_AJAX_domain').'">'.$description.'</textarea>';
                echo '</div>';
            echo '</div>';
        }
        static function css_class($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_css_class braapf_full_select_full">';
                    $css_class = br_get_value_from_array($braapf_filter_settings, 'css_class', '');
                    echo '<label for="braapf_css_class">'.__('CSS Class', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input id="braapf_css_class" type="text" name="' . $settings_name . '[css_class]" value="'.$css_class.'">';
                    echo '<small>' . __('(use white space for multiple classes)', 'BeRocket_AJAX_domain') . '</small>';
                echo '</div>';
            echo '</div>';
        }
        static function filter_height_scroll($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_height braapf_half_select_full">';
                    $height = br_get_value_from_array($braapf_filter_settings, 'height', '');
                    echo '<label for="braapf_height">'.__('Height of the Filter Block', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input min="0" id="braapf_height" type="text" name="' . $settings_name . '[height]" value="'.$height.'" placeholder="'.__('Auto', 'BeRocket_AJAX_domain').'">';
                echo '</div>';
                echo '<div class="braapf_scroll_theme braapf_half_select_full">';
                    $scroll_theme = br_get_value_from_array($braapf_filter_settings, 'scroll_theme', '');
                    echo '<label for="braapf_scroll_theme">' . __('Scrollbar theme', 'BeRocket_AJAX_domain') . '</label>';
                    $scroll_themes = array(
                        "light",
                        "dark",
                        "minimal",
                        "minimal-dark",
                        "light-2",
                        "dark-2",
                        "light-3",
                        "dark-3",
                        "light-thick",
                        "dark-thick",
                        "light-thin",
                        "dark-thin",
                        "inset",
                        "inset-dark",
                        "inset-2",
                        "inset-2-dark",
                        "inset-3",
                        "inset-3-dark",
                        "rounded",
                        "rounded-dark",
                        "rounded-dots",
                        "rounded-dots-dark",
                        "3d",
                        "3d-dark",
                        "3d-thick",
                        "3d-thick-dark"
                    );
                    echo '<select id="braapf_scroll_theme" name="'.$settings_name.'[scroll_theme]">';
                        foreach($scroll_themes as $scroll_theme_val) {
                            echo '<option value="'.$scroll_theme_val.'"'.($scroll_theme_val == $scroll_theme ? ' selected' : '').'>' . $scroll_theme_val . '</option>';
                        }
                    echo '</select>';
                echo '</div>';
            echo '</div>';
        }
        static function icons($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_icon_before_title braapf_half_select_full">';
                    $icon_before_title = br_get_value_from_array($braapf_filter_settings, 'icon_before_title', '');
                    echo berocket_font_select_upload(__('Icon Before Title', 'BeRocket_AJAX_domain'), 'icon_before_title', $settings_name.'[icon_before_title]', $icon_before_title );
                echo '</div>';
                echo '<div class="braapf_icon_after_title braapf_half_select_full">';
                    $icon_after_title = br_get_value_from_array($braapf_filter_settings, 'icon_after_title', '');
                    echo berocket_font_select_upload(__('Icon After Title', 'BeRocket_AJAX_domain'), 'icon_after_title', $settings_name.'[icon_after_title]', $icon_after_title );
                echo '</div>';
            echo '</div>';
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_icon_before_value braapf_half_select_full">';
                    $icon_before_value = br_get_value_from_array($braapf_filter_settings, 'icon_before_value', '');
                    echo berocket_font_select_upload(__('Icon Before Value', 'BeRocket_AJAX_domain'), 'icon_before_value', $settings_name.'[icon_before_value]', $icon_before_value );
                echo '</div>';
                echo '<div class="braapf_icon_after_value braapf_half_select_full">';
                    $icon_after_value = br_get_value_from_array($braapf_filter_settings, 'icon_after_value', '');
                    echo berocket_font_select_upload(__('Icon After Value', 'BeRocket_AJAX_domain'), 'icon_after_value', $settings_name.'[icon_after_value]', $icon_after_value );
                echo '</div>';
            echo '</div>';
        }
        //COLOR/IMAGE
        static function display_name_with_color_image($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_use_value_with_color braapf_half_select_full">';
                    $use_value_with_color = br_get_value_from_array($braapf_filter_settings, 'use_value_with_color', '');
                    echo '<label for="braapf_use_value_with_color">' . __('Display value next to color/image?', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_use_value_with_color" name="'.$settings_name.'[use_value_with_color]">';
                        echo '<option value=""'.($use_value_with_color == "" ? ' selected' : '').'>' . __('Disabled', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="top"'.($use_value_with_color == "top" ? ' selected' : '').'>' . __('Top', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="left"'.($use_value_with_color == "left" ? ' selected' : '').'>' . __('Left', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="right"'.($use_value_with_color == "right" ? ' selected' : '').'>' . __('Right', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="bottom"'.($use_value_with_color == "bottom" ? ' selected' : '').'>' . __('Bottom', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="tooltip"'.($use_value_with_color == "tooltip" ? ' selected' : '').'>' . __('Tooltip', 'BeRocket_AJAX_domain') . '</option>';
                    echo '</select>';
                echo '</div>';
                echo '<div class="braapf_color_image_block_size braapf_half_select_full">';
                    $color_image_block_size = br_get_value_from_array($braapf_filter_settings, 'color_image_block_size', '');
                    echo '<label for="braapf_color_image_block_size">' . __('Size of blocks(Height x Width)', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_color_image_block_size" name="'.$settings_name.'[color_image_block_size]">';
                    $color_image_sizes = array(
                        'h2em w2em' => __('2em x 2em', 'BeRocket_AJAX_domain'),
                        'h1em w1em' => __('1em x 1em', 'BeRocket_AJAX_domain'),
                        'h1em w2em' => __('1em x 2em', 'BeRocket_AJAX_domain'),
                        'h2em w3em' => __('2em x 3em', 'BeRocket_AJAX_domain'),
                        'h2em w4em' => __('2em x 4em', 'BeRocket_AJAX_domain'),
                        'h3em w3em' => __('3em x 3em', 'BeRocket_AJAX_domain'),
                        'h3em w4em' => __('3em x 4em', 'BeRocket_AJAX_domain'),
                        'h3em w5em' => __('3em x 5em', 'BeRocket_AJAX_domain'),
                        'h4em w4em' => __('4em x 4em', 'BeRocket_AJAX_domain'),
                        'h4em w5em' => __('4em x 5em', 'BeRocket_AJAX_domain'),
                        'h5em w5em' => __('5em x 5em', 'BeRocket_AJAX_domain'),
                        'hxpx_wxpx' => __('Custom size', 'BeRocket_AJAX_domain'),
                    );
                    foreach($color_image_sizes as $color_image_size_id => $color_image_size_name) {
                        echo '<option value="'.$color_image_size_id.'"'.($color_image_block_size == $color_image_size_id ? ' selected' : '').'>' . $color_image_size_name . '</option>';
                    }
                    echo '</select>';
                    echo '<div class="braapf_color_image_block_size_custom">';
                        $color_image_block_size_height = br_get_value_from_array($braapf_filter_settings, 'color_image_block_size_height', '');
                        echo '<input min="0" id="braapf_color_image_block_size_height" type="number" name="' . $settings_name . '[color_image_block_size_height]" value="'.$color_image_block_size_height.'" placeholder="50">';
                        echo 'px x';
                        $color_image_block_size_width = br_get_value_from_array($braapf_filter_settings, 'color_image_block_size_width', '');
                        echo '<input min="0" id="braapf_color_image_block_size_width" type="number" name="' . $settings_name . '[color_image_block_size_width]" value="'.$color_image_block_size_width.'" placeholder="50">';
                        echo 'px';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
        static function selection_type($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_color_image_checked braapf_half_select_full">';
                    $color_image_checked = br_get_value_from_array($braapf_filter_settings, 'color_image_checked', '');
                    echo '<label for="braapf_color_image_checked">' . __('Selected value style', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_color_image_checked" name="'.$settings_name.'[color_image_checked]">';
                    $color_image_sizes = array(
                        'brchecked_default' => __('Default', 'BeRocket_AJAX_domain'),
                        'brchecked_rotate' => __('Rotate', 'BeRocket_AJAX_domain'),
                        'brchecked_scale' => __('Scale', 'BeRocket_AJAX_domain'),
                        'brchecked_shadow' => __('Blue Shadow', 'BeRocket_AJAX_domain'),
                        'brchecked_image_shadow' => __('Drop-shadow(EXPERIMENTAL)', 'BeRocket_AJAX_domain'),
                        'brchecked_hue_rotate' => __('Color Change(EXPERIMENTAL)', 'BeRocket_AJAX_domain'),
                        'brchecked_custom' => __('Custom CSS', 'BeRocket_AJAX_domain'),
                    );
                    foreach($color_image_sizes as $color_image_size_id => $color_image_size_name) {
                        echo '<option value="'.$color_image_size_id.'"'.($color_image_checked == $color_image_size_id ? ' selected' : '').'>' . $color_image_size_name . '</option>';
                    }
                    echo '</select>';
                echo '</div>';
                echo '<div class="braapf_color_image_checked_custom_css braapf_half_select_full">';
                    $color_image_checked_custom_css = br_get_value_from_array($braapf_filter_settings, 'color_image_checked_custom_css', '');
                    echo '<label for="braapf_color_image_checked_custom_css">'.__('Custom CSS for Checked block', 'BeRocket_AJAX_domain').'</label>';
                    echo '<textarea id="braapf_color_image_checked_custom_css" name="' . $settings_name . '[color_image_checked_custom_css]">'.$color_image_checked_custom_css.'</textarea>';
                echo '</div>';
            echo '</div>';
        }
        //PRICE ATTRIBUTE
        static function price_values($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_price_values braapf_full_select_full">';
                    $price_values = br_get_value_from_array($braapf_filter_settings, 'price_values', '');
                    echo '<label for="braapf_price_values">'.__('Use custom values(comma separated)', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input id="braapf_price_values" type="text" name="' . $settings_name . '[price_values]" value="'.$price_values.'" placeholder="'.__('Use default price values', 'BeRocket_AJAX_domain').'">';
                    echo '<small>' . __('* use numeric values only, strings will not work as expected', 'BeRocket_AJAX_domain') . '</small>';
                echo '</div>';
            echo '</div>';
        }
        static function min_max_price_values($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_min_price braapf_half_select_full">';
                    $min_price = br_get_value_from_array($braapf_filter_settings, 'min_price', '');
                    echo '<label for="braapf_min_price">'.__('Use custom minimum price', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input min="0" id="braapf_min_price" type="number" name="' . $settings_name . '[min_price]" value="'.$min_price.'" placeholder="'.__('From Products List', 'BeRocket_AJAX_domain').'">';
                echo '</div>';
                echo '<div class="braapf_max_price braapf_half_select_full">';
                    $max_price = br_get_value_from_array($braapf_filter_settings, 'max_price', '');
                    echo '<label for="braapf_max_price">'.__('Use custom maximum price', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input min="0" id="braapf_max_price" type="number" name="' . $settings_name . '[max_price]" value="'.$max_price.'" placeholder="'.__('From Products List', 'BeRocket_AJAX_domain').'">';
                echo '</div>';
            echo '</div>';
        }
        static function text_before_after_price($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_text_before_price braapf_half_select_full">';
                    $text_before_price = br_get_value_from_array($braapf_filter_settings, 'text_before_price', '');
                    echo '<label for="braapf_text_before_price">'.__('Text before Slider value', 'BeRocket_AJAX_domain').'
                    <span id="braapf_text_before_price_info" class="dashicons dashicons-editor-help"></span></label>';
                    echo '<input id="braapf_text_before_price" type="text" name="' . $settings_name . '[text_before_price]" value="'.$text_before_price.'">';
                echo '</div>';
                echo '<div class="braapf_text_after_price braapf_half_select_full">';
                    $text_after_price = br_get_value_from_array($braapf_filter_settings, 'text_after_price', '');
                    echo '<label for="braapf_text_after_price">'.__('Text after Slider value', 'BeRocket_AJAX_domain').'
                    <span id="braapf_text_after_price_info" class="dashicons dashicons-editor-help"></span></label>';
                    echo '<input id="braapf_text_after_price" type="text" name="' . $settings_name . '[text_after_price]" value="'.$text_after_price.'">';
                echo '</div>';
            echo '</div>';
            
            $tooltip_text = '<strong>' . __('You can use some replacements', 'BeRocket_AJAX_domain') . '</strong>'
            . '<ul><li><i>%cur_symbol%</i> - ' . __('currency symbol($)', 'BeRocket_AJAX_domain') . '</li>'
            . '<li><i>%cur_slug%</i> - ' . __('currency code(USD)', 'BeRocket_AJAX_domain') . '</li></ul>';
            BeRocket_tooltip_display::add_tooltip(
                array(
                    'appendTo'      => 'document.body',
                    'arrow'         => true,
                    'interactive'   => true, 
                    'placement'     => 'top'
                ),
                $tooltip_text,
                '#braapf_text_after_price_info'
            );
            BeRocket_tooltip_display::add_tooltip(
                array(
                    'appendTo'      => 'document.body',
                    'arrow'         => true,
                    'interactive'   => true, 
                    'placement'     => 'top'
                ),
                $tooltip_text,
                '#braapf_text_before_price_info'
            );
        }
        static function specific_number_styles($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_number_style braapf_half_select_full">';
                    $number_style = br_get_value_from_array($braapf_filter_settings, 'number_style', '0');
                    echo '<p>';
                        echo '<input id="braapf_number_style" type="checkbox" name="' . $settings_name . '[number_style]"' . ( empty($number_style) ? '' : ' checked' ) . ' value="1">';
                        echo '<label for="braapf_number_style">'.__('Use specific number style', 'BeRocket_AJAX_domain').'</label>';
                    echo '</p>';
                echo '</div>';
                echo '<div class="braapf_number_style_elements braapf_half_select_full">';
                    $number_style_thousand_separate = br_get_value_from_array($braapf_filter_settings, 'number_style_thousand_separate', '');
                    echo '<label for="braapf_number_style_thousand_separate">'.__('Thousands separator', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input id="braapf_number_style_thousand_separate" type="text" name="' . $settings_name . '[number_style_thousand_separate]" value="'.$number_style_thousand_separate.'">';
                    $number_style_decimal_separate = br_get_value_from_array($braapf_filter_settings, 'number_style_decimal_separate', '');
                    echo '<label for="braapf_number_style_decimal_separate">'.__('Decimal separator', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input id="braapf_number_style_decimal_separate" type="text" name="' . $settings_name . '[number_style_decimal_separate]" value="'.$number_style_decimal_separate.'">';
                    $number_style_decimal_number = br_get_value_from_array($braapf_filter_settings, 'number_style_decimal_number', '');
                    echo '<label for="braapf_number_style_decimal_number">'.__('Number of digits after decimal point', 'BeRocket_AJAX_domain').'</label>';
                    echo '<input min=0 id="braapf_number_style_decimal_number" type="number" name="' . $settings_name . '[number_style_decimal_number]" value="'.$number_style_decimal_number.'">';
                echo '</div>';
            echo '</div>';
        }
        static function selected_filters_area($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_selected_area_show braapf_full_select_full">';
                    $selected_area_show = br_get_value_from_array($braapf_filter_settings, 'selected_area_show', '0');
                    echo '<p>';
                        echo '<input id="braapf_selected_area_show" type="checkbox" name="' . $settings_name . '[selected_area_show]"' . ( empty($selected_area_show) ? '' : ' checked' ) . ' value="1">';
                        echo '<label for="braapf_selected_area_show">'.__('Show if nothing is selected', 'BeRocket_AJAX_domain').'</label>';
                    echo '</p>';
                echo '</div>';
            echo '</div>';
        }
        static function reset_button_hide($settings_name, $braapf_filter_settings) {
            echo '<div class="braapf_attribute_setup_flex">';
                echo '<div class="braapf_reset_hide braapf_half_select_full">';
                    $reset_hide = br_get_value_from_array($braapf_filter_settings, 'reset_hide', '');
                    echo '<label for="braapf_reset_hide">' . __('Hide button', 'BeRocket_AJAX_domain') . '</label>';
                    echo '<select id="braapf_reset_hide" name="'.$settings_name.'[reset_hide]">';
                        echo '<option value=""'.($reset_hide == "" ? ' selected' : '').'>' . __('Do not hide', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="bapf_rst_nofltr"'.($reset_hide == "bapf_rst_nofltr" ? ' selected' : '').'>' . __('Hide only when no filters on page', 'BeRocket_AJAX_domain') . '</option>';
                        echo '<option value="bapf_rst_nofltr bapf_rst_sel"'.($reset_hide == "bapf_rst_nofltr bapf_rst_sel" ? ' selected' : '').'>' . __('Hide when no filters on page or page not filtered', 'BeRocket_AJAX_domain') . '</option>';
                    echo '</select>';
                echo '</div>';
            echo '</div>';
        }
        static function save_button($settings_name, $braapf_filter_settings) {
            echo '<input type="submit" name="publish" class="button button-primary" value="'.__('SAVE FILTER', 'BeRocket_AJAX_domain').'">';
        }
        //Helper functions
        static function get_custom_taxonomies() {
            $custom_taxonomies = get_object_taxonomies( 'product' );
            $custom_taxonomies_list = array();
            foreach($custom_taxonomies as $taxonomy_name) {
                $custom_taxonomy = get_taxonomy($taxonomy_name);
                $custom_taxonomies_list[$taxonomy_name] = array(
                    'name' => $custom_taxonomy->label,
                    'hierarchical' => empty($custom_taxonomy->hierarchical) ? 0 : 1
                );
            }
            $custom_taxonomies_list = apply_filters('braapf_custom_taxonomy_elements', $custom_taxonomies_list);
            return $custom_taxonomies_list;
        }
        static function get_all_filter_type_array($braapf_filter_settings) {
            $filter_type_array = array(
                'price' => array(
                    'name' => __('Price', 'BeRocket_AJAX_domain'),
                    'sameas' => 'price',
                    'templates' => array('slider', 'new_slider'),
                    'positions' => array('20000', '10000'),
                    'specific'  => array(''),
                    'spec_pos'  => array('1000'),
                ),
                'attribute' => array(
                    'name' => __('Attribute', 'BeRocket_AJAX_domain'),
                    'sameas' => 'attribute',
                    'templates' => array('checkbox', 'select'),
                    'positions' => array('10000', '20000'),
                    'specific'  => array('', 'color', 'image'),
                    'spec_pos'  => array('1000', '2000', '3000'),
                ),
                'tag' => array(
                    'name' => __('Tag', 'BeRocket_AJAX_domain'),
                    'sameas' => 'custom_taxonomy',
                    'attribute' => 'product_tag',
                    'templates' => array('checkbox', 'select'),
                    'positions' => array('10000', '20000'),
                    'specific'  => array('', 'color', 'image'),
                    'spec_pos'  => array('1000', '2000', '3000'),
                ),
                'all_product_cat' => array(
                    'name' => __('Product Category', 'BeRocket_AJAX_domain'),
                    'sameas' => 'custom_taxonomy',
                    'attribute' => 'product_cat',
                    'templates' => array('checkbox', 'select'),
                    'positions' => array('10000', '20000'),
                    'specific'  => array('', 'color', 'image'),
                    'spec_pos'  => array('1', '2', '3'),
                ),
            );
            if ( function_exists('wc_get_product_visibility_term_ids') ) {
                $filter_type_array['_rating'] = array(
                    'name' => __('Rating', 'BeRocket_AJAX_domain'),
                    'sameas' => '_rating',
                    'templates' => array('checkbox', 'select'),
                    'positions' => array('10000', '20000'),
                    'specific'  => array(''),
                    'spec_pos'  => array('1000'),
                );
            }
            $filter_type_array = apply_filters('berocket_filter_filter_type_array', $filter_type_array, $braapf_filter_settings);
            foreach($filter_type_array as &$filter_type) {
                if( br_get_value_from_array($filter_type, array('sameas')) == 'custom_taxonomy' ) {
                    if( empty($filter_type['templates']) ) {
                        $filter_type['templates'] = $filter_type_array['attribute']['templates'];
                        $filter_type['positions'] = $filter_type_array['attribute']['positions'];
                    }
                    if( empty($filter_type['specific']) ) {
                        $filter_type['specific'] = $filter_type_array['attribute']['specific'];
                        $filter_type['spec_pos'] = $filter_type_array['attribute']['spec_pos'];
                    }
                }
            }
            if( isset($filter_type) ) {
                unset($filter_type);
            }
            return $filter_type_array;
        }
        static function get_all_style_template_data() {
            $style_templates = array(
                'checkbox' => array(
                    0       => __('Check Box', 'BeRocket_AJAX_domain'),
                    'color' => __('Color', 'BeRocket_AJAX_domain'),
                    'image' => __('Image', 'BeRocket_AJAX_domain'),
                ),
                'select' => array(
                    0  => __('Drop Down Menu', 'BeRocket_AJAX_domain')
                ),
                'datepicker' => array(
                    0  => __('Date Picker', 'BeRocket_AJAX_domain')
                ),
                'slider' => array(
                    0  => __('Slider Old', 'BeRocket_AJAX_domain')
                ),
                'new_slider' => array(
                    0  => __('Slider New', 'BeRocket_AJAX_domain')
                ),
            );
            return apply_filters('braapf_all_style_template_data', $style_templates);
        }
        static function get_curent_taxonomy_name($braapf_filter_settings) {
            $filter_type_array = self::get_all_filter_type_array($braapf_filter_settings);
            $filter_type = br_get_value_from_array($braapf_filter_settings, 'filter_type', 'price');
            $type = ( (! empty($filter_type) && ! empty($filter_type_array[$filter_type])) ? $filter_type_array[$filter_type]['sameas'] : false );
            $taxonomy_name = false;
            if( $type == 'attribute' || $type == 'custom_taxonomy' ) {
                $taxonomy_name = (empty($filter_type_array[$filter_type]['attribute']) ? br_get_value_from_array($braapf_filter_settings, $type, false) : $filter_type_array[$filter_type]['attribute']);
            }
            if( empty($taxonomy_name) ) {
                $taxonomy_name = false;
            }
            return $taxonomy_name;
        }
    }
    new braapf_single_filter_edit_elements();
}
