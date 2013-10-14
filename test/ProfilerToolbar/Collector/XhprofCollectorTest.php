<?php

namespace Skpd\ProfilerToolbarTest\Collector;

use FlightPath\Configurator\Bootstrap;
use Skpd\ProfilerToolbar\Collector\XhprofCollector;
use Zend\Mvc\MvcEvent;

class XhprofCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldCollectProfilingData()
    {
        $event     = new MvcEvent();
        $collector = new XhprofCollector();

        xhprof_enable(XHPROF_FLAGS_MEMORY);

        $collector->collect($event);

        $heaps = $collector->getHeaps();

        $this->assertArrayHasKey('memory', $heaps);
        $this->assertArrayHasKey('time', $heaps);
        $this->assertArrayHasKey('call', $heaps);

        $this->assertCount(3, $heaps['call']);
        $this->assertCount(3, $heaps['time']);
        $this->assertCount(3, $heaps['memory']);
    }

    public function testShouldAvoidEmptyData()
    {
        $event     = new MvcEvent();
        $collector = new XhprofCollector();

        $collector->collect($event);
    }

    public function testShouldCreateInstance()
    {
        $collector = Bootstrap::getServiceManager()->get('Skpd\ProfilerToolbar\ProfilerCollector');

        $this->assertInstanceOf('Skpd\ProfilerToolbar\Collector\XhprofCollector', $collector);
    }
}