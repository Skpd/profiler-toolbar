<?php

namespace Skpd\ProfilerToolbar;

return [
    'service_manager' => [
        'invokables' => [
            'ProfilerToolbar\ProfilerCollector' => 'ProfilerToolbar\Collector\XhprofCollector'
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [ __DIR__ . '/../view' ],
    ],
];