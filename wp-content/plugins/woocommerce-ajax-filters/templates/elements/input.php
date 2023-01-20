<?php
extract($berocket_query_var_title);
//Get default template functionality
$template_content = BeRocket_AAPF_Template_Build_default();
unset($template_content['template']['attributes']['data-op']);
unset($template_content['template']['attributes']['data-taxonomy']);
//Set name for selected filters area and other siilar place
$template_content['template']['attributes']['data-name']                                    = berocket_isset($title);
//Set widget title
$template_content['template']['content']['header']['content']['title']['content']['title']  = berocket_isset($title);
$template_content['template']['content']['filter']['content']['form'] = array(
    'type'          => 'tag',
    'tag'           => 'form',
    'attributes'    => array(
        'class'         => array(
            'bapf_form'
        ),
    ),
    'content'       => array(
        'input' => array(
            'type'          => 'tag',
            'tag'           => 'input',
            'attributes'    => array(
                'class'         => array(
                    'bapf_input'
                ),
                'type'      => 'text',
                'name'      => 's'
            ),
            'content'       => array()
        )
    )
);
$template_content = apply_filters('BeRocket_AAPF_template_full_element_content', $template_content, $berocket_query_var_title);
echo BeRocket_AAPF_Template_Build($template_content);
