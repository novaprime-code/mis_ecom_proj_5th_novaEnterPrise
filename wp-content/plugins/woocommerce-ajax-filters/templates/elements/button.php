<?php
extract($berocket_query_var_title);
//Get default template functionality
$template_content = BeRocket_AAPF_Template_Build_default();
unset($template_content['template']['attributes']['data-op']);
unset($template_content['template']['attributes']['data-taxonomy']);
unset($template_content['template']['attributes']['data-name']);
unset($template_content['template']['content']['header']);
$template_content['template']['content']['filter']['content']['button'] = array(
    'type'          => 'tag',
    'tag'           => 'button',
    'attributes'    => array(
        'class'         => array(
            'bapf_button'
        )
    ),
    'content'       => array(
        berocket_isset($title)
    )
);
$template_content = apply_filters('BeRocket_AAPF_template_full_element_content', $template_content, $berocket_query_var_title);
echo BeRocket_AAPF_Template_Build($template_content);
