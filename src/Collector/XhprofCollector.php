<?php

namespace Skpd\ProfilerToolbar\Collector;

use Skpd\ProfilerToolbar\Entry;
use Skpd\ProfilerToolbar\MaxHeap;
use Skpd\ProfilerToolbar\Options;
use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\AbstractCollector;

class XhprofCollector extends AbstractCollector
{
    /** @var Options */
    private $options;

    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * Collector Name.
     *
     * @return string
     */
    public function getName()
    {
        return 'profiler';
    }

    /**
     * Collector Priority.
     *
     * @return integer
     */
    public function getPriority()
    {
        return 9999;
    }

    /**
     * Collects data.
     *
     * @param MvcEvent $mvcEvent
     */
    public function collect(MvcEvent $mvcEvent)
    {
        $rawData = xhprof_disable();
        $rawData = empty($rawData) ? [] : $rawData;

        $data = $this->getFunctions($rawData);

        $heaps = [];

        foreach ($this->options->getMetrics() as $name => $options) {
            $this->data[$name] = [];
            $heaps[$name] = new MaxHeap();
        }

        if (!empty($data)) {

            foreach ($data as $entry) {
                /** @var Entry $entry */

                foreach ($this->options->getMetrics() as $name => $options) {
                    if ($options['skipInternal'] && $entry->isInternal()) {
                        continue;
                    }

                    if (!empty($options['skipPattern']) && preg_match($options['skipPattern'], $entry->getName())) {
                        continue;
                    }

                    $heaps[$name]->insert([
                        'name'  => $entry->getName(),
                        'value' => $entry->{$options['getter']}()
                    ]);
                }
            }

            foreach ($this->options->getMetrics() as $name => $options) {
                for ($i = $options['limit']; $i--;) {
                    $value = $heaps[$name]->extract();
                    $this->data[$name][$value['name']] = $value['value'];
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getHeaps()
    {
        return $this->data;
    }

    private function getFunctions(array $data)
    {
        $data = array_reverse($data, true);

        $collection = [
            'byName'  => [],
            'byOrder' => []
        ];
        $position = 0;

        $internalFunctions = get_defined_functions();
        $internalFunctions = array_flip($internalFunctions['internal']);

        foreach ($data as $name => $values) {
            $entry = new Entry();

            $entry->setCalls($values['ct']);
            $entry->setInclusiveTime($values['wt']);
            $entry->setInclusiveMemory($values['pmu']);

            $caller = explode('==>', $name, 2);

            if (count($caller) === 1) {
                if (isset($collection['byOrder'][$position - 1])) {
                    $entry->setCaller($collection['byOrder'][$position - 1]);
                }

                $entry->setName($caller[0]);
            } else {
                if (isset($collection['byName'][$caller[0]])) {
                    /** @var Entry $parent */
                    $parent = $collection['byName'][$caller[0]];
                } else {
                    $searchPosition = $position;

                    while (isset($collection['byOrder'][--$searchPosition])) {
                        if ($collection['byOrder'][$searchPosition]->getName() === $caller[0]) {
                            /** @var Entry $parent */
                            $parent = $collection['byOrder'][$searchPosition];
                            break;
                        }
                    }
                }

                if (!empty($parent)) {
                    $parent->addCallee($entry);
                    $entry->setCaller($parent);
                }

                $entry->setName($caller[1]);
            }

            $entry->setInternal(array_key_exists($entry->getName(), $internalFunctions));

            $collection['byOrder'][$position++] = $entry;
            $collection['byName'][$name]        = $entry;
        }

        foreach ($collection['byName'] as $entry) {
            $this->countExclusive($entry);
        }

        return $collection['byName'];
    }

    private function countExclusive(Entry $entry)
    {
        if ($entry->getExclusiveTime() === null) {
            $entry->setExclusiveTime($entry->getInclusiveTime());
            foreach ($entry->getCallees() as $callee) {
                $this->countExclusive($callee);
                $entry->setExclusiveTime($entry->getExclusiveTime() - ($callee->getInclusiveTime() / $callee->getCalls()));
            }
        }

        if ($entry->getExclusiveMemory() === null) {
            $entry->setExclusiveMemory($entry->getInclusiveMemory());
            foreach ($entry->getCallees() as $callee) {
                $this->countExclusive($callee);
                $entry->setExclusiveMemory($entry->getExclusiveMemory() - ($callee->getInclusiveMemory() / $callee->getCalls()));
            }
        }
    }
}