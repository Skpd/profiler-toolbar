<?php

namespace Skpd\ProfilerToolbar;

class MaxHeap extends \SplMaxHeap
{
    protected function compare($value1, $value2)
    {
        return ($value1['value'] - $value2['value']);
    }
}