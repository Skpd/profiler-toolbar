<?php

namespace Skpd\ProfilerToolbar;

return [
    'zenddevelopertools' => [
        'profiler' => [
            'collectors' => [
                'profiler' => 'ProfilerToolbar\ProfilerCollector',
            ],
        ],
        'toolbar' => [
            'entries' => array(
                'profiler' => 'profiler-toolbar/xhprof',
            ),
        ]
    ]
];