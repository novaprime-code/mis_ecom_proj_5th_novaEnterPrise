
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
$date_style = br_get_value_from_array($berocket_query_var_title, 'date_style');
$template_content = BeRocket_AAPF_Template_Build_default();
//set unique id for filter
$filter_unique_class = 'bapf_'.$unique_filter_id;
$template_content['template']['attributes']['id']                                           = $filter_unique_class;
//set this template class 
$template_content['template']['attributes']['class']['filter_type']                         = 'bapf_datepick';
//Set data for filter links
$template_content['template']['attributes']['data-op']                                      = 'slidr';
$template_content['template']['attributes']['data-taxonomy']                                = ( berocket_isset($term, 'wpml_taxonomy') ? $term->wpml_taxonomy : $term->taxonomy );
//Set name for selected filters area and other siilar place
$template_content['template']['attributes']['data-name']                                    = $title;
//Set widget title
$template_content['template']['content']['header']['content']['title']['content']['title']  = $title;
//Add widget content
$template_content['template']['content']['filter']['content']['datepicker_all']                 = array(
    'type'          => 'tag',
    'tag'           => 'div',
    'attributes'    => array(
        'class'         => array(
            'bapf_date_all'
        ),
        'style'     => array(),
        'data-min'          => floatval(berocket_isset($term, 'min')),
        'data-start'        => floatval(berocket_isset($term, 'min')),
        'data-max'          => floatval(berocket_isset($term, 'max')),
        'data-end'          => floatval(berocket_isset($term, 'max')),
        'data-step'         => floatval(berocket_isset($term, 'step')),
        'data-dateFormat'   => ( empty($date_style) ? 'mm/dd/yy' : str_replace(array('Y', 'm', 'd'), array('yy', 'mm', 'dd'), $date_style)),
        'data-changeMonth'  => ! empty($date_change_month),
        'data-changeYear'   => ! empty($date_change_year),
    ),
    'content'       => array(
        'from'  => array(
            'type'          => 'tag',
            'tag'           => 'span',
            'attributes'    => array(
                'class'         => array(
                    'bapf_date_from'
                )
            ),
            'content'       => array(
                'input'         => array(
                    'type'          => 'tag_open',
                    'tag'           => 'input',
                    'attributes'    => array(
                        'type'          => 'text',
                        'size'          => '9'
                    ),
                )
            ),
        ),
        'to'  => array(
            'type'          => 'tag',
            'tag'           => 'span',
            'attributes'    => array(
                'class'         => array(
                    'bapf_date_to'
                )
            ),
            'content'       => array(
                'input'         => array(
                    'type'          => 'tag_open',
                    'tag'           => 'input',
                    'attributes'    => array(
                        'type'          => 'text',
                        'size'          => '9'
                    ),
                )
            ),
        ),
    )
);
$template_content = apply_filters('BeRocket_AAPF_template_full_content', $template_content, $terms, $berocket_query_var_title);
if( ! empty($template_content) && berocket_isset($template_content['template']['content']['filter']['content']['datepicker_all']['attributes']['data-min']) < berocket_isset($template_content['template']['content']['filter']['content']['datepicker_all']['attributes']['data-max']) ) {
    echo BeRocket_AAPF_Template_Build($template_content);
}
