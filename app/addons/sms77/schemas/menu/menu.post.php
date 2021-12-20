<?php defined('BOOTSTRAP') || die('Access denied');

$schema['top']['administration']['items']['sms77.title'] = [
    'attrs' => [
        'class' => 'is-addon',
    ],
    'href' => 'addons.update&addon=sms77',
    'position' => 0,
    'subitems' => [
        'sms77_bulk' => [
            'attrs' => [
                'class' => 'is-addon',
            ],
            'href' => 'sms77_bulk.view',
            'position' => 0,
            'title' => __('sms77.bulk_sms'),
        ],
        'sms77_logs' => [
            'attrs' => [
                'class' => 'is-addon',
            ],
            'href' => 'sms77_history.view',
            'position' => 0,
            'title' => __('sms77.sms_history'),
        ],
        'sms77_test' => [
            'attrs' => [
                'class' => 'is-addon',
            ],
            'href' => 'sms77_test.view',
            'position' => 0,
            'title' => __('sms77.test_sms'),
        ],
    ],
    'title' => 'sms77',
];

return $schema;
