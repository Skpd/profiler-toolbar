<?php

namespace Skpd\ProfilerToolbarTest;

use Skpd\ProfilerToolbar\Entry;
use PHPUnit_Framework_TestCase;

class EntryTest extends PHPUnit_Framework_TestCase
{
    /** @var Entry */
    private $entry;

    public function setUp()
    {
        $this->entry = new Entry;
    }

    public function testName()
    {
        $this->entry->setName('testFunction');
        $this->assertSame('testFunction', $this->entry->getName());
    }

    public function testCaller()
    {
        $caller = new Entry;
        $caller->setName('testCaller');

        $this->entry->setCaller($caller);
        $this->assertSame('testCaller', $this->entry->getCaller()->getName());
    }

    public function testCallees()
    {
        $callee = new Entry;
        $callee->getName('testCallee');

        $this->entry->setCallees([$callee]);
        $this->assertEquals(1, count($this->entry->getCallees()));
        $this->assertSame($callee, current($this->entry->getCallees()));
    }

    public function testCalls()
    {
        $this->entry->setCalls(100500);
        $this->assertEquals(100500, $this->entry->getCalls());
    }

    public function testInclusiveTime()
    {
        $this->entry->setInclusiveTime(100500);
        $this->assertEquals(100500, $this->entry->getInclusiveTime());
    }

    public function testExclusiveTime()
    {
        $this->entry->setExclusiveTime(100500);
        $this->assertEquals(100500, $this->entry->getExclusiveTime());
    }

    public function testInclusiveMemory()
    {
        $this->entry->setInclusiveMemory(100500);
        $this->assertEquals(100500, $this->entry->getInclusiveMemory());
    }

    public function testExclusiveMemory()
    {
        $this->entry->setExclusiveMemory(100500);
        $this->assertEquals(100500, $this->entry->getExclusiveMemory());
    }

    public function testInternal()
    {
        $this->entry->setInternal(true);
        $this->assertTrue($this->entry->isInternal());
    }
}