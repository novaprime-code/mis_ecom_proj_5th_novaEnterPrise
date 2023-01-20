<?php return array(
    'root' => array(
        'name' => 'facebookincubator/facebook-for-woocommerce',
        'pretty_version' => 'dev-release/3.0.8',
        'version' => 'dev-release/3.0.8',
        'reference' => 'e3de7432419ba391b15977c166acc5a4fe0984e8',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => false,
    ),
    'versions' => array(
        'composer/installers' => array(
            'pretty_version' => 'v1.12.0',
            'version' => '1.12.0.0',
            'reference' => 'd20a64ed3c94748397ff5973488761b22f6d3f19',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/./installers',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'facebookincubator/facebook-for-woocommerce' => array(
            'pretty_version' => 'dev-release/3.0.8',
            'version' => 'dev-release/3.0.8',
            'reference' => 'e3de7432419ba391b15977c166acc5a4fe0984e8',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'roundcube/plugin-installer' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'shama/baton' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
        'woocommerce/action-scheduler-job-framework' => array(
            'pretty_version' => '2.0.0',
            'version' => '2.0.0.0',
            'reference' => 'b0b21b9cc87e476ba7f8817050b39274ea7d6732',
            'type' => 'library',
            'install_path' => __DIR__ . '/../woocommerce/action-scheduler-job-framework',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
