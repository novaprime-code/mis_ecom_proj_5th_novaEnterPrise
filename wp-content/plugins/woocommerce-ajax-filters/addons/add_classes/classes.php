<?php
class BeRocket_aapf_add_classes_filters {
    function __construct() {
        add_filter('BeRocket_AAPF_template_full_content', array($this, 'global_classes'), 1400, 4);
        add_filter('BeRocket_AAPF_template_single_item', array($this, 'products_count'), 1400, 4);
    }
    function global_classes($template_content, $terms, $berocket_query_var_title) {
        $template_content['template']['attributes']['class']['attribute'] = 'bapf_attr_' . trim($berocket_query_var_title['attribute']);
        return $template_content;
    }
    function products_count($element, $term, $i, $berocket_query_var_title) {
        $element = BeRocket_AAPF_dynamic_data_template::create_element_arrays($element, array('attributes', 'class'));
        $element['attributes']['class']['term_tax'] = 'bapf_tax_'.$term->taxonomy;
        $element['attributes']['class']['term_slug'] = 'bapf_term_'.$term->slug;
        $element['attributes']['class']['term_id'] = 'bapf_term_'.$term->term_id;
        $element['attributes']['class']['term_count'] = 'bapf_count_'.$term->count;
        $element['attributes']['class']['term_parent'] = 'bapf_parent_'.(property_exists($term, 'parent') ? $term->parent : '0');
        return $element;
    }
}
new BeRocket_aapf_add_classes_filters();