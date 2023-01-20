<?php
if( ! class_exists('BeRocket_AAPF_Template_Style_slider') ) {
    class BeRocket_AAPF_Template_Style_slider extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'slider',
                'template'      => 'slider',
                'name'          => 'Slider',
                'file'          => __FILE__,
                'style_file'    => '',
                'script_file'   => '/js/slider.js',
                'image'         => plugin_dir_url( __FILE__ ) . 'images/slider.png',
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
    new BeRocket_AAPF_Template_Style_slider();
}
if( ! class_exists('BeRocket_AAPF_Template_Style_slider_after') ) {
    class BeRocket_AAPF_Template_Style_slider_after extends BeRocket_AAPF_Template_Style_slider {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'slider_after';
            $this->data['name'] = 'Slider After';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/slider-after.png';
            $this->data['sort_pos'] = '900';
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            $template = parent::template_full($template, $terms, $berocket_query_var_title);
            $from = $template['template']['content']['filter']['content']['slider_all']['content']['from'];
            $to = $template['template']['content']['filter']['content']['slider_all']['content']['to'];
            unset($template['template']['content']['filter']['content']['slider_all']['content']['from']);
            unset($template['template']['content']['filter']['content']['slider_all']['content']['to']);
            $template['template']['content']['filter']['content']['slider_all']['content'] = berocket_insert_to_array(
                $template['template']['content']['filter']['content']['slider_all']['content'],
                'slider',
                array(
                    'from' => $from,
                    'to' => $to,
                )
            );
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_slider_after();
}
