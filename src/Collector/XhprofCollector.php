<?php

namespace Skpd\ProfilerToolbar\Collector;

use Skpd\ProfilerToolbar\Entry;
use Skpd\ProfilerToolbar\MaxHeap;
use Zend\Mvc\MvcEvent;
use ZendDeveloperTools\Collector\AbstractCollector;

class XhprofCollector extends AbstractCollector
{
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
        $data = xhprof_disable();

        $this->data = [
            'memory' => [],
            'time' => [],
            'call' => [],
        ];

        if (!empty($data)) {
            $memory = new MaxHeap();
            $time = new MaxHeap();
            $call = new MaxHeap();

            foreach ($data as $name => $values) {
                if (!preg_match('/(?:Zend\\\\|Composer\\\\)/i', $name)) {
                    $memory->insert(['name' => $name, 'value' => $values['pmu']]);
                    $time->insert(['name' => $name, 'value' => $values['wt']]);
                    $call->insert(['name' => $name, 'value' => $values['ct']]);
                }
            }

            for ($i = 3; $i--;) {
                $value = $memory->extract();
                $this->data['memory'][$value['name']] = round($value['value'] / 1024 / 1024, 2) . ' Mb';

                $value = $time->extract();
                $this->data['time'][$value['name']] = round($value['value'] / 1000, 3) . ' ms';

                $value = $call->extract();
                $this->data['call'][$value['name']] = $value['value'];
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
            $entry->setExclusiveTime($values['wt']);
            $entry->setInclusiveMemory($values['pmu']);
            $entry->setExclusiveMemory($values['pmu']);

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

                    $parent->addCallee($entry);
                    $parent->setExclusiveTime($parent->getExclusiveTime() - $entry->getInclusiveTime());
                    $parent->setExclusiveMemory($parent->getExclusiveMemory() - $entry->getInclusiveMemory());

                    $entry->setCaller($parent);
                }

                $entry->setName($caller[1]);
            }

            $entry->setIsInternal(array_key_exists($entry->getName(), $internalFunctions));

            $collection['byOrder'][$position++] = $entry;
            $collection['byName'][$name]        = $entry;
        }

        return $collection['byName'];
    }
}