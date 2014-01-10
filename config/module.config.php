<?php

namespace Skpd\ProfilerToolbar;

return [
    'service_manager' => [
        'invokables' => [
            'Skpd\ProfilerToolbar\ProfilerCollector' => 'Skpd\ProfilerToolbar\Collector\XhprofCollector'
        ],
        'factories' => [
            'Skpd\ProfilerToolbar\Options' => function ($serviceManager) {
                /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
                $config = $serviceManager->get('Configuration');
                $config = isset($config['profiler-toolbar']) ? $config['profiler-toolbar'] : [];

                return new Options($config);
            }
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [ __DIR__ . '/../view' ],
    ],
];