<?php
/**
* The template for displaying checkbox filters
*
* Override this template by copying it to yourtheme/woocommerce-ajax_filters/select.php
*
* @author     BeRocket
* @package     WooCommerce-Filters/Templates
* @version  1.0.1
*/
extract($berocket_query_var_title);
foreach($terms as $term){break;}
//Get default template functionality
$template_content = BeRocket_AAPF_Template_Build_default();
//set unique id for filter
$filter_unique_class = 'bapf_'.$unique_filter_id;
$template_content['template']['attributes']['id']                                           = $filter_unique_class;
//set this template class 
$template_content['template']['attributes']['class']['filter_type']                         = 'bapf_slct';
//Set data for filter links
$template_content['template']['attributes']['data-op']                                      = $operator;
$template_content['template']['attributes']['data-taxonomy']                                = ( berocket_isset($term, 'wpml_taxonomy') ? $term->wpml_taxonomy : $term->taxonomy );
//Set name for selected filters area and other siilar place
$template_content['template']['attributes']['data-name']                                    = $title;
//Set widget title
$template_content['template']['content']['header']['content']['title']['content']['title']  = $title;
//Add widget content
$template_content['template']['content']['filter']['content']['list']                       = array(
    'type'          => 'tag',
    'tag'           => 'select',
    'attributes'    => array(),
    'content'       => array()
);
$terms_content = array();
$element_unique = $filter_unique_class.'_any';
$terms_content['element_any'] = array(
    'type'          => 'tag',
    'tag'           => 'option',
    'attributes'    => array(
        'data-name'     => '',
        'id'            => $element_unique,
        'value'         => ''
    ),
    'content'       => array(
        $select_first_element_text
    )
);
foreach( $terms as $i => $term ) {
    $element_unique = $filter_unique_class.'_'.$term->term_id;
    $terms_content['element_'.$i] = apply_filters('BeRocket_AAPF_template_single_item', array(
        'type'          => 'tag',
        'tag'           => 'option',
        'attributes'    => array(
            'data-name'     => $term->name,
            'id'            => $element_unique,
            'value'         => $term->value
        ),
        'content'       => array(
            $term->name
        )
    ), $term, $i, $berocket_query_var_title);
}
$template_content['template']['content']['filter']['content']['list']['content'] = $terms_content;
$template_content = apply_filters('BeRocket_AAPF_template_full_content', $template_content, $terms, $berocket_query_var_title);
echo BeRocket_AAPF_Template_Build($template_content);
