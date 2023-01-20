<?php
extract($berocket_query_var_title);
//Get default template functionality
$template_content = BeRocket_AAPF_Template_Build_default();
unset($template_content['template']['attributes']['data-op']);
unset($template_content['template']['attributes']['data-taxonomy']);
unset($template_content['template']['attributes']['data-name']);
//Set name for selected filters area and other siilar place
$template_content['template']['attributes']['data-name']                                    = $title;
//Set widget title
$template_content['template']['content']['header']['content']['title']['content']['title']  = $title;
$template_content['template']['content']['filter']['content']['selected_filters'] = array(
    'type'          => 'tag',
    'tag'           => 'div',
    'attributes'    => array(
        'class'         => array(
            'berocket_aapf_widget_selected_area'
        )
    ),
    'content'       => array()
);
$template_content = apply_filters('BeRocket_AAPF_template_full_element_content', $template_content, $berocket_query_var_title);
echo BeRocket_AAPF_Template_Build($template_content);
