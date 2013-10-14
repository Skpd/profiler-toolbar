<?php

namespace Skpd\ProfilerToolbar;

return [
    'service_manager' => [
        'invokables' => [
            'Skpd\ProfilerToolbar\ProfilerCollector' => 'Skpd\ProfilerToolbar\Collector\XhprofCollector'
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [ __DIR__ . '/../view' ],
    ],
];