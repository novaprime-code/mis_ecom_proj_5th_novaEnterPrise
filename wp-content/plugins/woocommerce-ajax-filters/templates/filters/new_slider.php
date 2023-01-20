
<?php
/**
* The template for displaying slider filters
*
* Override this template by copying it to yourtheme/woocommerce-ajax_filters/slider.php
*
* @author     BeRocket
* @package     WooCommerce-Filters/Templates
* @version  1.0.1
*/
extract($berocket_query_var_title);
foreach($terms as $term){break;}
$template_content = BeRocket_AAPF_Template_Build_default();
//set unique id for filter
$filter_unique_class = 'bapf_'.$unique_filter_id;
$template_content['template']['attributes']['id']                                           = $filter_unique_class;
//set this template class 
$template_content['template']['attributes']['class']['filter_type']                         = 'bapf_slidr';
$template_content['template']['attributes']['class']['filter_type_second']                  = 'bapf_slidr_ion';
//Set data for filter links
$template_content['template']['attributes']['data-op']                                      = 'slidr';
$template_content['template']['attributes']['data-taxonomy']                                = ( berocket_isset($term, 'wpml_taxonomy') ? $term->wpml_taxonomy : $term->taxonomy );
//Set name for selected filters area and other siilar place
$template_content['template']['attributes']['data-name']                                    = $title;
//Set widget title
$template_content['template']['content']['header']['content']['title']['content']['title']  = $title;
//Add widget content
$template_content['template']['content']['filter']['content']['slider_all']                 = array(
    'type'          => 'tag',
    'tag'           => 'div',
    'attributes'    => array(
        'class'         => array(
            'bapf_slidr_all'
        ),
        'style'     => array()
    ),
    'content'       => array(
        'slider' => array(
            'type'          => 'tag_open',
            'tag'           => 'input',
            'attributes'    => array(
                'class'         => array(
                                         'bapf_slidr_main',
                    'bapf_slidr_type' => 'bapf_slidr_num'
                ),
                'type'          => 'text',
                'data-display'  => berocket_isset($slider_display_data),
                'data-min'      => floatval(berocket_isset($term, 'min')),
                'data-start'    => floatval(berocket_isset($term, 'min')),
                'data-max'      => floatval(berocket_isset($term, 'max')),
                'data-end'      => floatval(berocket_isset($term, 'max')),
                'data-step'     => floatval(berocket_isset($term, 'step')),
            ),
        )
    )
);
$template_content = apply_filters('BeRocket_AAPF_template_full_content', $template_content, $terms, $berocket_query_var_title);
if( ! empty($template_content) && berocket_isset($template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-min']) < berocket_isset($template_content['template']['content']['filter']['content']['slider_all']['content']['slider']['attributes']['data-max']) ) {
    echo BeRocket_AAPF_Template_Build($template_content);
}
