<?php

namespace Skpd\ProfilerToolbar;

return [
    'service_manager' => [
        'factories' => [
            'Skpd\ProfilerToolbar\ProfilerCollector' => function ($serviceManager) {
                /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
                /** @var Options $options */
                $options = $serviceManager->get('Skpd\ProfilerToolbar\Options');

                return new Collector\XhprofCollector($options);
            },
            'Skpd\ProfilerToolbar\Options' => function ($serviceManager) {
                /** @var \Zend\ServiceManager\ServiceManager $serviceManager */
                $config = $serviceManager->get('Configuration');
                $config = isset($config['profiler-toolbar']) ? $config['profiler-toolbar'] : null;

                return new Options($config);
            },
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [ __DIR__ . '/../view' ],
    ],
];