<?php
if( ! class_exists('BeRocket_AAPF_Template_Style_new_slider') ) {
    class BeRocket_AAPF_Template_Style_new_slider extends BeRocket_AAPF_Template_Style {
        function __construct() {
            $this->data = array(
                'slug'          => 'new_slider',
                'template'      => 'new_slider',
                'name'          => 'New Slider',
                'file'          => __FILE__,
                'style_file'    => '/css/ion.rangeSlider.min.css',
                'script_file'   => '/js/ion.rangeSlider.min.js',
                'image'         => plugin_dir_url( __FILE__ ) . 'images/new-slider.png',
                'version'       => '1.0',
                'sort_pos'      => '1',
            );
            parent::__construct();
        }
        function enqueue_all() {
            parent::enqueue_all();
            if( file_exists(dirname($this->data['file']).'/js/newSlider.js') ) {
                BeRocket_AAPF::wp_enqueue_script( 'BeRocket_AAPF_script-add-'.sanitize_title('/js/newSlider.js'), plugins_url( '/js/newSlider.js', $this->data['file'] ), array('jquery'), $this->data['version'], true );
            }
        }
    }
    new BeRocket_AAPF_Template_Style_new_slider();
}
if( ! class_exists('BeRocket_AAPF_Template_Style_new_slider_big') ) {
    class BeRocket_AAPF_Template_Style_new_slider_big extends BeRocket_AAPF_Template_Style_new_slider {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'new_slider_big';
            $this->data['name'] = 'New Slider Big';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/new-slider-big.png';
            $this->data['sort_pos'] = '900';
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            $template['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-skin'] = 'big';
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_new_slider_big();
}
if( ! class_exists('BeRocket_AAPF_Template_Style_new_slider_modern') ) {
    class BeRocket_AAPF_Template_Style_new_slider_modern extends BeRocket_AAPF_Template_Style_new_slider {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'new_slider_modern';
            $this->data['name'] = 'New Slider Modern';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/new-slider-modern.png';
            $this->data['sort_pos'] = '900';
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            $template['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-skin'] = 'modern';
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_new_slider_modern();
}
if( ! class_exists('BeRocket_AAPF_Template_Style_new_slider_sharp') ) {
    class BeRocket_AAPF_Template_Style_new_slider_sharp extends BeRocket_AAPF_Template_Style_new_slider {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'new_slider_sharp';
            $this->data['name'] = 'New Slider Sharp';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/new-slider-sharp.png';
            $this->data['sort_pos'] = '900';
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            $template['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-skin'] = 'sharp';
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_new_slider_sharp();
}
if( ! class_exists('BeRocket_AAPF_Template_Style_new_slider_round') ) {
    class BeRocket_AAPF_Template_Style_new_slider_round extends BeRocket_AAPF_Template_Style_new_slider {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'new_slider_round';
            $this->data['name'] = 'New Slider Round';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/new-slider-round.png';
            $this->data['sort_pos'] = '900';
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            $template['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-skin'] = 'round';
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_new_slider_round();
}
if( ! class_exists('BeRocket_AAPF_Template_Style_new_slider_square') ) {
    class BeRocket_AAPF_Template_Style_new_slider_square extends BeRocket_AAPF_Template_Style_new_slider {
        function __construct() {
            parent::__construct();
            $this->data['slug'] = 'new_slider_square';
            $this->data['name'] = 'New Slider Square';
            $this->data['image'] = plugin_dir_url( __FILE__ ) . 'images/new-slider-square.png';
            $this->data['sort_pos'] = '900';
        }
        function template_full($template, $terms, $berocket_query_var_title) {
            $template['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-skin'] = 'square';
            return $template;
        }
    }
    new BeRocket_AAPF_Template_Style_new_slider_square();
}
