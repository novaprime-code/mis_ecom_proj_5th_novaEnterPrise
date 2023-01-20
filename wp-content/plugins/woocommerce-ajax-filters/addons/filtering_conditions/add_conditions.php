<?php
class BeRocket_filtering_conditions_AAPF extends BeRocket_conditions {
    public static function get_conditions() {
        $conditions = parent::get_conditions();
        $conditions['condition_attribute_filtering'] = array(
            'func' => 'check_condition_attribute_filtering',
            'type' => 'attribute_filtering',
            'name' => __('Attribute', 'BeRocket_domain')
        );
        return $conditions;
    }
    public static function condition_attribute_filtering($html, $name, $options) {
        $html = self::condition_product_attribute($html, $name, $options);
        $html .= '<label><input type="checkbox" '.(empty($options['is_example']) ? '' : 'data-').'name="' . $name . '[include_sub_attr]"'.(empty($options['include_sub_attr']) ? '' : ' checked').' value="1">'.__('Include sub-attributes', 'BeRocket_AJAX_domain').'</label>';
        return $html;
    }

        public static function check_condition_attribute_filtering($show, $condition, $additional) {
            $selected_terms = br_get_selected_term($condition['attribute']);
            $show = count($selected_terms) > 0;
            if( $show && $condition['values'][$condition['attribute']] !== '' ) {
                $show = in_array($condition['values'][$condition['attribute']], $selected_terms);
                if( ! $show && ! empty($condition['include_sub_attr']) ) {
                    $terms = get_term_children($condition['values'][$condition['attribute']], $condition['attribute']);
                    $show = false;
                    foreach($terms as $term) {
                        if ( in_array($term, $selected_terms) ) {
                            $show = true;
                            break;
                        }
                    }
                }
            }
            if( $condition['equal'] == 'not_equal' ) {
                $show = ! $show;
            }
            return $show;
        }
}
class BeRocket_aapf_filtering_conditions {
    public $post_name;
    public $AAPF_single_filter;
    public $hook_name = 'berocket_aapf_filtering_conditions';
    public $conditions;
    function __construct() {
        $this->AAPF_single_filter = BeRocket_AAPF_single_filter::getInstance();
        $this->post_name = $this->AAPF_single_filter->post_name;
        add_action('ajax_filters_framework_construct', array($this, 'init_conditions'));
        add_filter('BeRocket_AAPF_widget_old_display_conditions', array($this, 'check_conditions'), 10, 4);
        add_filter('BeRocket_AAPF_widget_additional_classes', array($this, 'additional_class'), 10, 3);
        $this->AAPF_single_filter->add_meta_box('filtering_conditions', __( 'Nested Filters (BETA)', 'BeRocket_AJAX_domain' ), array($this, 'conditions'));
    }
    public function init_conditions() {
        $this->conditions = new BeRocket_filtering_conditions_AAPF($this->post_name.'[data2]', $this->hook_name, array(
            'condition_attribute_filtering'
        ));
    }
    public function conditions($post) {
        echo '<p>'.__( 'Use this to display products only after filtering by some attribute and value', 'BeRocket_AJAX_domain' ).'</p>';
        $options = $this->AAPF_single_filter->get_option( $post->ID );
        echo $this->conditions->build($options['data2']);
    }
    function check_conditions($show, $filter_data, $instance, $args) {
        $options = $this->AAPF_single_filter->get_option( $instance['filter_id'] );
        $show = empty($options['data2']) || $this->conditions->check($options['data2'], $this->hook_name);
        return $show;
    }
    function additional_class($additional_class, $filter_id, $filter_data) {
        if( ! empty($filter_data['data2']) ) {
            $additional_class[] = 'bapf_partload';
        }
        return $additional_class;
    }
}
new BeRocket_aapf_filtering_conditions();
