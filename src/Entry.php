<?php

namespace Skpd\ProfilerToolbar;

class Entry
{
    private $name;

    private $caller;
    private $callees = [];

    private $calls;
    private $inclusiveTime;
    private $exclusiveTime;
    private $inclusiveMemory;
    private $exclusiveMemory;

    private $isInternal;

    /**
     * @param int $exclusiveMemory
     */
    public function setExclusiveMemory($exclusiveMemory)
    {
        $this->exclusiveMemory = $exclusiveMemory;
    }

    /**
     * @return int
     */
    public function getExclusiveMemory()
    {
        return $this->exclusiveMemory;
    }

    /**
     * @param bool $isInternal
     */
    public function setIsInternal($isInternal)
    {
        $this->isInternal = $isInternal;
    }

    /**
     * @return bool
     */
    public function getIsInternal()
    {
        return $this->isInternal;
    }

    /**
     * @return bool
     */
    public function isInternal()
    {
        return $this->isInternal;
    }

    /**
     * @param Entry[] $callees
     */
    public function setCallees($callees)
    {
        $this->callees = $callees;
    }

    /**
     * @return Entry[]
     */
    public function getCallees()
    {
        return $this->callees;
    }

    public function addCallee($callee)
    {
        $this->callees[] = $callee;
    }

    /**
     * @param Entry $caller
     */
    public function setCaller($caller)
    {
        $this->caller = $caller;
    }

    /**
     * @return Entry|null
     */
    public function getCaller()
    {
        return $this->caller;
    }

    /**
     * @param int $calls
     */
    public function setCalls($calls)
    {
        $this->calls = $calls;
    }

    /**
     * @return int
     */
    public function getCalls()
    {
        return $this->calls;
    }

    /**
     * @param int $memory
     */
    public function setInclusiveMemory($memory)
    {
        $this->inclusiveMemory = $memory;
    }

    /**
     * @return int
     */
    public function getInclusiveMemory()
    {
        return $this->inclusiveMemory;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $time
     */
    public function setInclusiveTime($time)
    {
        $this->inclusiveTime = $time;
    }

    /**
     * @return int
     */
    public function getInclusiveTime()
    {
        return $this->inclusiveTime;
    }

    /**
     * @param int $time
     */
    public function setExclusiveTime($time)
    {
        $this->exclusiveTime = $time;
    }

    /**
     * @return int
     */
    public function getExclusiveTime()
    {
        return $this->exclusiveTime;
    }
}

