<?php

namespace Skpd\ProfilerToolbar;

use Zend\Stdlib\AbstractOptions;

class Options extends AbstractOptions
{
    private $metrics;

    /**
     * @param $metrics
     * @throws \InvalidArgumentException
     */
    public function setMetrics($metrics)
    {
        if (!is_array($metrics)) {
            throw new \InvalidArgumentException('Metrics should be an array');
        }

        $reflection = new \ReflectionClass('Skpd\ProfilerToolbar\Entry');

        foreach ($metrics as $name => $options) {
            $getter = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', strtolower($name))));

            if (!$reflection->hasMethod($getter)) {
                throw new \InvalidArgumentException(sprintf('Unknown metric "%s"', $name));
            }

            $this->metrics[$name] = [
                'getter' => $getter
            ];

            if (isset($options['limit'])) {
                if (!is_numeric($options['limit']) || $options['limit'] < 0) {
                    throw new \InvalidArgumentException('Limit must be positive number.');
                }

                $this->metrics[$name]['limit'] = $options['limit'];
            } else {
                $this->metrics[$name]['limit'] = 3;
            }

            if (isset($options['skipInternal'])) {
                $this->metrics[$name]['skipInternal'] = $options['skipInternal'];
            } else {
                $this->metrics[$name]['skipInternal'] = false;
            }

            if (!isset($options['skipPattern'])) {
                if (!empty($options['skipNamespace'])) {
                    if (!is_array($options['skipNamespace'])) {
                        $options['skipNamespace'] = [$options['skipNamespace']];
                    }

                    foreach ($options['skipNamespace'] as $k => $v) {
                        $options['skipNamespace'][$k] = $v . '\\\\';
                    }

                    $this->metrics[$name]['skipPattern'] = '/(?:' . implode('|', $options['skipNamespace']) . ')/i';
                }
            } else {
                $this->metrics[$name]['skipPattern'] = $options['skipPattern'];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getMetrics()
    {
        return $this->metrics;
    }
}