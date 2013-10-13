<?php

namespace Skpd\ProfilerToolbarTest\Collector;

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
}