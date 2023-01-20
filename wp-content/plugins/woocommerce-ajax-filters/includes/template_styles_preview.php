<?php
if( ! class_exists('BeRocket_AAPF_styles_preview_button') ) {
    class BeRocket_AAPF_styles_preview_button extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'button_preview',
                'template'      => 'button',
                'name'          => 'Button Preview',
                'file'          => __FILE__,
                'style_file'    => '',
                'script_file'   => '',
                'image'         => '',
                'version'       => '1.0',
                'specific'      => 'elements',
                'sort_pos'      => '10000',
            );
            parent::__construct();
        }
        function filters($action = 'add') {
            parent::filters($action);
            $filter_func = 'add_filter';
            $action_func = 'add_action';
            if( $action != 'add' ) {
                $filter_func = 'remove_filter';
                $action_func = 'remove_action';
            }
            $filter_func('BeRocket_AAPF_template_full_element_content', array($this, 'template_element_full'), 10, 2);
        }
        function template_element_full($template, $berocket_query_var_title) {
            return $template;
        }
    }
}
if( ! class_exists('BeRocket_AAPF_styles_preview_color') ) {
    class BeRocket_AAPF_styles_preview_color extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'color_preview',
                'template'      => 'checkbox',
                'name'          => 'Color Preview',
                'file'          => __FILE__,
                'style_file'    => '',
                'script_file'   => '',
                'image'         => '',
                'version'       => '1.0',
                'specific'      => 'color',
                'sort_pos'      => '10000',
            );
            parent::__construct();
        }
        function template_full($template_content, $terms, $berocket_query_var_title) {
            $template_content = parent::template_full($template_content, $terms, $berocket_query_var_title);
            $template_content['template']['attributes']['class']['style_type'] = 'bapf_stylecolor';
            $template_content['template']['attributes']['class']['inline_color'] = 'bapf_colorinline';
            $template_content = $this->template_full_custom($template_content, $terms, $berocket_query_var_title);
            return $template_content;
        }
        function template_full_custom($template_content, $terms, $berocket_query_var_title) {
            return $template_content;
        }
        function template_single_item($template, $term, $i, $berocket_query_var_title) {
            $template = parent::template_single_item($template, $term, $i, $berocket_query_var_title);
            $berocket_term = berocket_term_get_metadata($term, 'color');
            $meta_color = br_get_value_from_array($berocket_term, 0, '');
            $meta_color = str_replace('#', '', $meta_color);
            $template['content']['checkbox'] = BeRocket_AAPF_dynamic_data_template::create_element_arrays($template['content']['checkbox'], array('attributes', 'style'));
            $template['content']['checkbox']['attributes']['style']['display'] = 'display:none;';
            $template['content']['label']['content'] = array(
                'color' => array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'         => array(
                            'main'          => 'bapf_clr_span',
                        ),
                        'style'         => array(
                            'bg-color'      => 'background-color: #'.$meta_color.';'
                        ),
                    ),
                    'content'       => array(
                        'span'          => array(
                            'type'          => 'tag',
                            'tag'           => 'span',
                            'attributes'    => array(
                                'class'         => array(
                                    'main'          => 'bapf_clr_span_abslt',
                                ),
                            ),
                        )
                    )
                )
            );
            $template = $this->template_single_item_custom($template, $term, $i, $berocket_query_var_title);
            return $template;
        }
        function template_single_item_custom($template, $term, $i, $berocket_query_var_title) {
            return $template;
        }
    }
}
if( ! class_exists('BeRocket_AAPF_styles_preview_image') ) {
    class BeRocket_AAPF_styles_preview_image extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'image_preview',
                'template'      => 'checkbox',
                'name'          => 'Image Preview',
                'file'          => __FILE__,
                'style_file'    => '',
                'script_file'   => '',
                'image'         => '',
                'version'       => '1.0',
                'specific'      => 'image',
                'sort_pos'      => '1',
            );
            parent::__construct();
        }
        function template_full($template_content, $terms, $berocket_query_var_title) {
            $template_content = parent::template_full($template_content, $terms, $berocket_query_var_title);
            $template_content['template']['attributes']['class']['style_type'] = 'bapf_styleimage';
            $template_content['template']['attributes']['class']['inline_color'] = 'bapf_colorinline';
            $template_content = $this->template_full_custom($template_content, $terms, $berocket_query_var_title);
            return $template_content;
        }
        function template_full_custom($template_content, $terms, $berocket_query_var_title) {
            return $template_content;
        }
        function template_single_item($template, $term, $i, $berocket_query_var_title) {
            $template = parent::template_single_item($template, $term, $i, $berocket_query_var_title);
            $berocket_term = berocket_term_get_metadata($term, 'image');
            $meta_image = br_get_value_from_array($berocket_term, 0, '');
            $template['content']['checkbox'] = BeRocket_AAPF_dynamic_data_template::create_element_arrays($template['content']['checkbox'], array('attributes', 'style'));
            $template['content']['checkbox']['attributes']['style']['display'] = 'display:none;';
            $template['content']['label']['content'] = array(
                'color' => array(
                    'type'          => 'tag',
                    'tag'           => 'span',
                    'attributes'    => array(
                        'class'         => array(
                            'main'          => 'bapf_img_span',
                        ),
                        'style'         => array(),
                    ),
                    'content'       => array(
                        'span'          => array(
                            'type'          => 'tag',
                            'tag'           => 'span',
                            'attributes'    => array(
                                'class'         => array(
                                    'main'          => 'bapf_clr_span_abslt',
                                ),
                            ),
                        )
                    )
                )
            );
            if ( substr( $meta_image, 0, 3) == 'fa-' ) {
                $template['content']['label']['content']['color']['content']['icon'] = array(
                    'type'          => 'tag',
                    'tag'           => 'i',
                    'attributes'    => array(
                        'class'         => array(
                            'main'          => 'fa',
                            'icon'          => $meta_image
                        ),
                        'style'         => array()
                    ),
                );
            } else {
                $template['content']['label']['content']['color']['attributes']['style']['bg-color'] = 'background: url('.$meta_image.') no-repeat scroll 50% 50% rgba(0, 0, 0, 0);background-size: cover;';
            }
            $template = $this->template_single_item_custom($template, $term, $i, $berocket_query_var_title);
            return $template;
        }
        function template_single_item_custom($template, $term, $i, $berocket_query_var_title) {
            return $template;
        }
    }
}
if( ! class_exists('BeRocket_AAPF_styles_preview_new_slider') ) {
    class BeRocket_AAPF_styles_preview_new_slider extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'new_slider_preview',
                'template'      => 'new_slider',
                'name'          => 'New Slider Preview',
                'file'          => __FILE__,
                'style_file'    => '/../template_styles/css/ion.rangeSlider.min.css',
                'script_file'   => '/../template_styles/js/ion.rangeSlider.min.js',
                'image'         => '',
                'version'       => '1.0',
                'sort_pos'      => '1',
            );
            parent::__construct();
        }
        function enqueue_all() {
            parent::enqueue_all();
            $file_include = '/../template_styles/js/newSlider.js';
            if( file_exists(dirname(__FILE__).$file_include) ) {
                BeRocket_AAPF::wp_enqueue_script( 'BeRocket_AAPF_script-add-'.sanitize_title('/js/newSlider.js'), plugins_url( $file_include, __FILE__ ), array('jquery'), $this->data['version'], true );
            }
            $file_include = '/../template_styles/js/ion.rangeSlider.min.js';
            if( file_exists(dirname(__FILE__).$file_include) ) {
                BeRocket_AAPF::wp_enqueue_script( 'BeRocket_AAPF_script-add-'.sanitize_title('/js/ion.rangeSlider.min.js'), plugins_url( $file_include, __FILE__ ), array('jquery'), $this->data['version'], true );
            }
            $file_include = '/../template_styles/css/ion.rangeSlider.min.css';
            if( file_exists(dirname($this->data['file']).$file_include) ) {
                BeRocket_AAPF::wp_enqueue_style( 'BeRocket_AAPF_style-'.sanitize_title('/css/ion.rangeSlider.min.css'), plugins_url( $file_include, __FILE__ ), array(), $this->data['version'] );
            }
        }
    }
}
if( ! class_exists('BeRocket_AAPF_styles_preview_selected_filters_area') ) {
    class BeRocket_AAPF_styles_preview_selected_filters_area extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'sfa_preview',
                'template'      => 'selected_filters',
                'name'          => 'Selected Filters Area Preview',
                'file'          => __FILE__,
                'style_file'    => '',
                'script_file'   => '',
                'image'         => '',
                'version'       => '1.0',
                'specific'      => 'elements',
                'sort_pos'      => '1',
            );
            parent::__construct();
        }
        function filters($action = 'add') {
            parent::filters($action);
            $filter_func = 'add_filter';
            $action_func = 'add_action';
            if( $action != 'add' ) {
                $filter_func = 'remove_filter';
                $action_func = 'remove_action';
            }
            $filter_func('BeRocket_AAPF_template_full_element_content', array($this, 'template_element_full'), 10, 2);
        }
        function template_element_full($template, $berocket_query_var_title) {
            return $template;
        }
    }
}
if( ! class_exists('BeRocket_AAPF_styles_preview_slider') ) {
    class BeRocket_AAPF_styles_preview_slider extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'slider_preview',
                'template'      => 'slider',
                'name'          => 'Slider Preview',
                'file'          => __FILE__,
                'style_file'    => '',
                'script_file'   => '/../template_styles/js/slider.js',
                'image'         => '',
                'version'       => '1.0',
                'sort_pos'      => '1',
            );
            parent::__construct();
        }
        function enqueue_all() {
            wp_enqueue_script( 'jquery-ui-slider' );
            BeRocket_AAPF::wp_enqueue_script( 'berocket_aapf_jquery-slider-fix');
            parent::enqueue_all();
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            return $template;
        }
    }
}