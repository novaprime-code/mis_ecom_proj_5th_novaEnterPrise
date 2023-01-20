<?php
$old_filter_widgets = get_option('widget_berocket_aapf_widget');
if( ! empty($old_filter_widgets) && is_array($old_filter_widgets) && count($old_filter_widgets) ) {
    $new_filter_widgets = get_option('widget_berocket_aapf_single');
    $new_widget_id = 1;
    if( !empty($new_filter_widgets) && is_array($new_filter_widgets) && count($new_filter_widgets) ) {
        foreach($new_filter_widgets as $filter_id => $filter_widget) {
            if( is_numeric($filter_id) && $filter_id > $new_widget_id ) {
                $new_widget_id = $filter_id;
            }
        }
    } else {
        $new_filter_widgets = array('_multiwidget' => 1);
    }
    $new_widget_id++;
    $new_filter_to_old = array();
    foreach($old_filter_widgets as $filter_id => $filter_widget) {
        if( is_numeric($filter_id) ) {
            $filter_widget = array_merge(BeRocket_AAPF_Widget::$defaults, $filter_widget);
            if( ! empty($filter_widget['show_page']) && is_array($filter_widget['show_page']) ) {
                $filter_widget['data'] = array();
                $i = 1;
                
                $compatibility_pages = array(
                    'product_cat' => 'category',
                    'product_taxonomy' => 'taxonomies',
                    'product_tag' => 'tags',
                    'single_product' => 'product',
                );
                $show_pages_fix = array();
                $product_cat = false;
                foreach($filter_widget['show_page'] as $show_page) {
                    if( isset($compatibility_pages[$show_page]) ) {
                        $show_page_fix = $compatibility_pages[$show_page];
                    } else {
                        $show_page_fix = $show_page;
                    }
                    if( $show_page_fix == 'category' ) {
                        $product_cat = true;
                    } else {
                        $show_pages_fix[] = $show_page_fix;
                    }
                }
                $filter_widget['data'][$i] = array(
                    '1' => array(
                        'equal' => 'equal',
                        'type' => 'page',
                        'pages' => $show_pages_fix,
                    ),
                );
                if( $product_cat ) {
                    $i++;
                    $filter_widget['data'][$i] = array(
                        '1' => array(
                            'equal' => 'equal',
                            'type' => 'page',
                            'pages' => array('category'),
                        ),
                    );
                    if( ! empty($filter_widget['product_cat']) && is_string($filter_widget['product_cat']) ) {
                        $filter_widget['product_cat'] = @ json_decode( $filter_widget['product_cat'] );
                    }
                    if( ! empty($filter_widget['product_cat']) && is_array($filter_widget['product_cat']) ) {
                        $cat_propagation = array();
                        foreach($filter_widget['product_cat'] as $product_cat) {
                            $product_cat = get_term_by('slug', $product_cat, 'product_cat');
                            if( is_object($product_cat) && property_exists($product_cat, 'term_id') ) {
                                $cat_propagation[] = $product_cat->term_id;
                            }
                        }
                        $filter_widget['data'][$i]['2'] = array(
                            'equal' => 'equal',
                            'type' => 'woo_category',
                            'subcats' => ! empty($filter_widget['cat_propagation']),
                            'category' => $cat_propagation,
                        );
                    }
                }
            }
            if( empty($filter_widget['widget_collapse_disable']) ) {
                $filter_widget['widget_collapse_enable'] = '1';
            } else {
                $filter_widget['widget_collapse_enable'] = '';
            }
            $post_data = array(
                'post_title'    => $filter_widget['title'],
                'post_content'  => '',
                'post_type'     => 'br_product_filter',
                'post_status'   => 'publish'
            );
            $BeRocket_AAPF_single_filter = BeRocket_AAPF_single_filter::getInstance();
            $post_id = $BeRocket_AAPF_single_filter->create_new_post($post_data, $filter_widget);
            $new_filter_widgets[$new_widget_id] = array('filter_id' => $post_id, 'custom_class' => '');
            $new_filter_to_old['berocket_aapf_widget-'.$filter_id] = 'berocket_aapf_single-'.$new_widget_id;
            $new_widget_id++;
        }
    }
    update_option('widget_berocket_aapf_widget', array());
    update_option('widget_berocket_aapf_single', $new_filter_widgets);
    $sidebars_widgets = get_option('sidebars_widgets');
    if( is_array($sidebars_widgets) && count($sidebars_widgets) ) {
        foreach($sidebars_widgets as &$sidebar_widgets) {
            if( is_array($sidebar_widgets) ) {
                foreach($sidebar_widgets as &$sidebar_widget) {
                    if( isset($new_filter_to_old[$sidebar_widget]) ) {
                        $sidebar_widget = $new_filter_to_old[$sidebar_widget];
                    }
                }
                if( isset($sidebar_widget) ) {
                    unset($sidebar_widget);
                }
            }
        }
        if( isset($sidebar_widgets) ) {
            unset($sidebar_widgets);
        }
    }
    update_option('sidebars_widgets', $sidebars_widgets);
}
