<?php

namespace Skpd\ProfilerToolbarTest\Collector;

use FlightPath\Configurator\Bootstrap;
use Skpd\ProfilerToolbar\Collector\XhprofCollector;
use Skpd\ProfilerToolbar\Options;
use Zend\Mvc\MvcEvent;

class XhprofCollectorTest extends \PHPUnit_Framework_TestCase
{
    private function getOptions()
    {
        return new Options([
            'metrics' => [
                'inclusive-memory' => [],
                'inclusive-time' => [],
                'calls' => [],
            ],
        ]);
    }

    public function testShouldCollectProfilingData()
    {
        $event     = new MvcEvent();
        $collector = new XhprofCollector($this->getOptions());

        xhprof_enable(XHPROF_FLAGS_MEMORY);

        $collector->collect($event);

        $heaps = $collector->getHeaps();

        $this->assertArrayHasKey('inclusive-memory', $heaps);
        $this->assertArrayHasKey('inclusive-time', $heaps);
        $this->assertArrayHasKey('calls', $heaps);

        $this->assertCount(3, $heaps['calls']);
        $this->assertCount(3, $heaps['inclusive-time']);
        $this->assertCount(3, $heaps['inclusive-memory']);
    }

    public function testShouldAvoidEmptyData()
    {
        $event     = new MvcEvent();
        $collector = new XhprofCollector($this->getOptions());

        $collector->collect($event);
    }

    public function testShouldCreateInstance()
    {
        $collector = Bootstrap::getServiceManager()->get('Skpd\ProfilerToolbar\ProfilerCollector');

        $this->assertInstanceOf('Skpd\ProfilerToolbar\Collector\XhprofCollector', $collector);
    }
}