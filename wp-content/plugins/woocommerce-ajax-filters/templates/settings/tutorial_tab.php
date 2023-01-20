<?php
$tutorials = array(
    'beginning' => array(
        'title' => __('Beginning', 'BeRocket_AJAX_domain'),
        'elements' => array(
            'how_to_configure' => array(
                'title' => __('How to configure the plugin', 'BeRocket_AJAX_domain'),
                'type'  => 'youtube',
                'video' => 'Ltz82Zs5pl0'
            ),
            'instalation' => array(
                'title' => __('Installation and Activation', 'BeRocket_AJAX_domain'),
                'type'  => 'youtube',
                'video' => '9ymG2giG2r0'
            ),
        )
    ),
    'advanced' => array(
        'title' => __('Features', 'BeRocket_AJAX_domain'),
        'elements' => array(
            'custom_sidebar' => array(
                'title' => __('Custom Sidebar', 'BeRocket_AJAX_domain'),
                'type'  => 'youtube',
                'video' => 'GA3O1F6YVNE'
            ),
            'variation_filtering' => array(
                'title' => __('Setup filtering by variation', 'BeRocket_AJAX_domain'),
                'type'  => 'youtube',
                'video' => 'GPA77L0XBxM'
            ),
        )
    ),
    'builders' => array(
        'title' => __('Page Builders Compatibility', 'BeRocket_AJAX_domain'),
        'elements' => array(
            'divi' => array(
                'title' => __('Divi Builder', 'BeRocket_AJAX_domain'),
                'type'  => 'youtube',
                'video' => '4tkVYBelPKY'
            ),
            'elementor' => array(
                'title' => __('Elementor Builder', 'BeRocket_AJAX_domain'),
                'type'  => 'youtube',
                'video' => 'FAyzS0Z_JcA'
            ),
            'beaver' => array(
                'title' => __('Beaver Builder', 'BeRocket_AJAX_domain'),
                'type'  => 'youtube',
                'video' => 'AGjZO03Y7Ho'
            ),
            'divi' => array(
                'title' => __('Divi Builder', 'BeRocket_AJAX_domain'),
                'type'  => 'youtube',
                'video' => '4tkVYBelPKY'
            ),
        )
    )
);
echo berocket_tutorial_tab($tutorials);