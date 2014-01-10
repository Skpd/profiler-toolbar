<?php

return [
    'profiler-toolbar' => [
        'metrics' => [
            'inclusive-memory' => [
                'limit' => 5,
            ],
            'exclusive-time' => [
                'skipNamespace' => ['Zend', 'Composer'],
                'limit' => 5,
            ],
            'calls' => [
                'skipPattern'   => '/zend\\\\|composer\\\\|call_user_func/i',
                'skipInternal'  => true,
            ],
        ],
    ]
];