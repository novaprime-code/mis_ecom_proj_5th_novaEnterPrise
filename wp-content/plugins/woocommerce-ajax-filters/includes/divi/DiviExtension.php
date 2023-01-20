<?php

class BAPF_DiviExtension extends DiviExtension {

	public $gettext_domain = 'bapf-diviextension';

	public $name = 'bapf-diviextension';
	public $version = '1.0.0';
	public function __construct( $name = 'my-extension', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugins_url('/', __FILE__);

		parent::__construct( $name, $args );
        add_action('wp_ajax_brapf_get_single_filter', array($this, 'get_single_filter'));
        add_action('wp_ajax_brapf_get_group_filter', array($this, 'get_group_filter'));
	}
    public function get_single_filter() {
        $filter_id = (empty($_POST['filter_id']) ? '' : intval($_POST['filter_id']));
        if( ! empty($filter_id) ) {
            $filter = do_shortcode('[br_filter_single filter_id='.$filter_id.']');
            if( ! empty($filter) ) {
                $filter .= '<script>jQuery(ET_Builder.Frames.app.window).trigger("bapf_update_et_pb_br_filter_single");</script>';
            }
            echo $filter;
        }
        wp_die();
    }
    public function get_group_filter() {
        $group_id = (empty($_POST['group_id']) ? '' : intval($_POST['group_id']));
        if( ! empty($group_id) ) {
            $group = do_shortcode('[br_filters_group group_id='.$group_id.']');
            if( ! empty($group) ) {
                $group .= '<script>jQuery(ET_Builder.Frames.app.window).trigger("bapf_update_et_pb_br_filter_single");</script>';
            }
            echo $group;
        }
        wp_die();
    }
	public function wp_hook_enqueue_scripts() {
		if ( $this->_debug ) {
			$this->_enqueue_debug_bundles();
		} else {
			$this->_enqueue_bundles();
		}

		if ( et_core_is_fb_enabled() && ! et_builder_bfb_enabled() ) {
			$this->_enqueue_backend_styles();
		}

		// Normalize the extension name to get actual script name. For example from 'divi-custom-modules' to `DiviCustomModules`.
		$extension_name = str_replace( ' ', '', ucwords( str_replace( '-', ' ', $this->name ) ) );

		// Enqueue frontend bundle's data.
		if ( ! empty( $this->_frontend_js_data ) ) {
			wp_localize_script( "{$this->name}-frontend-bundle", "{$extension_name}FrontendData", $this->_frontend_js_data );
		}

		// Enqueue builder bundle's data.
		if ( et_core_is_fb_enabled() && ! empty( $this->_builder_js_data ) ) {
			wp_localize_script( "{$this->name}-builder-bundle", "{$extension_name}BuilderData", $this->_builder_js_data );
		}
	}
}

new BAPF_DiviExtension;
