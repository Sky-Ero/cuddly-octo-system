<?php return array(
    'root' => array(
        'name' => 'test_app/mvc',
        'pretty_version' => '1.0.0',
        'version' => '1.0.0.0',
        'reference' => null,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'core/mvc' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '9c22c5797644d6ca6d7fb7ddf9521e0af569e3d4',
            'type' => 'library',
            'install_path' => __DIR__ . '/../core/mvc',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'test_app/mvc' => array(
            'pretty_version' => '1.0.0',
            'version' => '1.0.0.0',
            'reference' => null,
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
