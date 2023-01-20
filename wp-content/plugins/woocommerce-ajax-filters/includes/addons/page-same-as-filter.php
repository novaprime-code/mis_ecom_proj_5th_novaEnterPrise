<?php
if( ! class_exists('BeRocket_AAPF_addon_page_same_as_filter') ) {
    class BeRocket_AAPF_addon_page_same_as_filter {
        function __construct($variant) {
            if( $variant == 'remove' ) {
                add_filter('berocket_aapf_widget_include_exclude_items', array($this, 'remove'), 100, 2);
            } elseif( $variant == 'leave' ) {
                add_filter('berocket_aapf_widget_include_exclude_items', array($this, 'leave'), 100, 2);
                add_filter('berocket_widget_load_template_name', array($this, 'leave_replace_template'), 10, 3);
                add_filter('BeRocket_AAPF_template_single_item', array($this, 'checkbox_disable'), 10, 4);
                add_filter('BeRocket_AAPF_template_full_content', array($this, 'select_disable'), 10, 4);
            }
        }
        function remove($terms, $instance) {
            if(get_queried_object_id() != 0 && ! empty($terms) && count($terms) ) {
                $queried_object = get_queried_object();
                $terms = array_values($terms);
                if( $terms[0]->taxonomy == $queried_object->taxonomy ) {
                    foreach($terms as $term_i => $term) {
                        if( $term->term_id == $queried_object->term_id ) {
                            unset($terms[$term_i]);
                            break;
                        }
                    }
                    $terms = array_values($terms);
                }
            }
            return $terms;
        }
        function leave($terms, $instance) {
            if(get_queried_object_id() != 0 && ! empty($terms) && count($terms) ) {
                $queried_object = get_queried_object();
                $terms = array_values($terms);
                if( $terms[0]->taxonomy == $queried_object->taxonomy ) {
                    foreach($terms as $term_i => $term) {
                        if( $term->term_id != $queried_object->term_id ) {
                            unset($terms[$term_i]);
                        }
                    }
                    $terms = array_values($terms);
                }
            }
            return $terms;
        }
        function leave_replace_template($type, $instance, $terms) {
            if(get_queried_object_id() != 0 && ! empty($terms) && count($terms) ) {
                $queried_object = get_queried_object();
                $terms = array_values($terms);
                if( $terms[0]->taxonomy == $queried_object->taxonomy ) {
                    $type = 'disabled/'.$type;
                }
            }
            return $type;
        }
        function checkbox_disable($element, $term, $i, $berocket_query_var_title) {
            if( $berocket_query_var_title['new_template'] == 'checkbox' && get_queried_object_id() != 0 ) {
                $queried_object = get_queried_object();
                if( $term->term_id == $queried_object->term_id ) {
                    $element = BeRocket_AAPF_dynamic_data_template::create_element_arrays($element, array('attributes', 'class'));
                    $element['attributes']['class'][] = 'bapf_disabled';
                    $element['content']['checkbox']['attributes']['disabled'] = 'disabled';
                    $element['content']['checkbox']['attributes']['checked'] = 'checked';
                }
            }
            return $element;
        }
        function select_disable($template_content, $terms, $berocket_query_var_title) {
            if( $berocket_query_var_title['new_template'] == 'select' && get_queried_object_id() != 0 ) {
                $queried_object = get_queried_object();
                if( $queried_object->taxonomy == $berocket_query_var_title['attribute'] ) {
                    $new_list = array();
                    foreach($terms as $i => $term) {
                        if( $term->term_id == $queried_object->term_id ) {
                            $new_list['element_'.$i] = $template_content['template']['content']['filter']['content']['list']['content']['element_'.$i];
                        }
                    }
                    if( count($new_list) ) {
                        $template_content['template']['content']['filter']['content']['list']['content'] = $new_list;
                        $template_content['template']['content']['filter']['content']['list']['attributes']['disabled'] = 'disabled';
                    }
                }
            }
            return $template_content;
        }
    }
}
