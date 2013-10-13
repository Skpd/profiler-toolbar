<?php

namespace Skpd\ProfilerToolbarTest;

use Skpd\ProfilerToolbar\MaxHeap;

class HeapTest extends \PHPUnit_Framework_TestCase
{
    public function testMaxHeapCompare()
    {
        $heap = new MaxHeap();

        $heap->insert(['value' => 3]);
        $heap->insert(['value' => 17]);
        $heap->insert(['value' => 1]);

        $this->assertEquals(['value' => 17], $heap->extract());
        $this->assertEquals(['value' => 3], $heap->extract());
        $this->assertEquals(['value' => 1], $heap->extract());
    }
}