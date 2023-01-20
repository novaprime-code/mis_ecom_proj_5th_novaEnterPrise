<?php
if( ! class_exists('BeRocket_AAPF_Template_Style_color') ) {
    class BeRocket_AAPF_Template_Style_color extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'color',
                'template'      => 'checkbox',
                'name'          => 'Color',
                'file'          => __FILE__,
                'style_file'    => '/css/color.css',
                'script_file'   => '',
                'image'         => plugin_dir_url( __FILE__ ) . 'images/color.png',
                'version'       => '1.0',
                'specific'      => 'color',
                'sort_pos'      => '1',
            );
            parent::__construct();
        }
        function template_full($template_content, $terms, $berocket_query_var_title) {
            $template_content['template']['attributes']['class']['style_type'] = 'bapf_stylecolor';
            $template_content['template']['attributes']['class']['inline_color'] = 'bapf_colorinline';
            return $template_content;
        }
        function template_single_item($template, $term, $i, $berocket_query_var_title) {
            $berocket_term = berocket_term_get_metadata($term, 'color');
            $meta_color = br_get_value_from_array($berocket_term, 0, '');
            $meta_color = str_replace('#', '', $meta_color);
            $meta_color = esc_attr($meta_color);
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
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_color();
}

if( ! class_exists('BeRocket_AAPF_Template_Style_color_woborder') ) {
    class BeRocket_AAPF_Template_Style_color_woborder extends BeRocket_AAPF_Template_Style_color {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'color_woborder';
            $this->data['name'] = 'Color without border';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/color_woborder.png';
            $this->data['version'] = '1.0';
            $this->data['sort_pos'] = '900';
        }
        function template_full($template_content, $terms, $berocket_query_var_title) {
            $template_content = parent::template_full($template_content, $terms, $berocket_query_var_title);
            $template_content['template']['attributes']['class']['img_woborder'] = 'bapf_clr_woborder';
            return $template_content;
        }
    }
    new BeRocket_AAPF_Template_Style_color_woborder();
}

if( ! class_exists('BeRocket_AAPF_Template_Style_image') ) {
    class BeRocket_AAPF_Template_Style_image extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'image',
                'template'      => 'checkbox',
                'name'          => 'Image',
                'file'          => __FILE__,
                'style_file'    => '/css/color.css',
                'script_file'   => '',
                'image'         => plugin_dir_url( __FILE__ ) . 'images/image.png',
                'version'       => '1.0',
                'specific'      => 'image',
                'sort_pos'      => '1',
            );
            parent::__construct();
        }
        function template_full($template_content, $terms, $berocket_query_var_title) {
            $template_content['template']['attributes']['class']['style_type'] = 'bapf_styleimage';
            $template_content['template']['attributes']['class']['inline_color'] = 'bapf_colorinline';
            return $template_content;
        }
        function template_single_item($template, $term, $i, $berocket_query_var_title) {
            $berocket_term = berocket_term_get_metadata($term, 'image');
            $meta_image = br_get_value_from_array($berocket_term, 0, '');
            $meta_image = esc_attr($meta_image);
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
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_image();
}

if( ! class_exists('BeRocket_AAPF_Template_Style_image_woborder') ) {
    class BeRocket_AAPF_Template_Style_image_woborder extends BeRocket_AAPF_Template_Style_image {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'image_woborder';
            $this->data['name'] = 'Image without border';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/image_woborder.png';
            $this->data['version'] = '1.0';
            $this->data['sort_pos'] = '900';
        }
        function template_full($template_content, $terms, $berocket_query_var_title) {
            $template_content = parent::template_full($template_content, $terms, $berocket_query_var_title);
            $template_content['template']['attributes']['class']['img_woborder'] = 'bapf_img_woborder';
            return $template_content;
        }
    }
    new BeRocket_AAPF_Template_Style_image_woborder();
}
