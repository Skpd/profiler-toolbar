<?php

namespace Skpd\ProfilerToolbar;

return [
    'zenddevelopertools' => [
        'profiler' => [
            'collectors' => [
                'profiler' => 'Skpd\ProfilerToolbar\ProfilerCollector',
            ],
        ],
        'toolbar' => [
            'entries' => array(
                'profiler' => 'profiler-toolbar/xhprof',
            ),
        ]
    ]
];