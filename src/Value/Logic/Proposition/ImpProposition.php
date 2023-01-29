<?php

namespace ModelChecking\Value\Logic\Proposition;

/**
 * 論理包含
 * A => B
 * 
 * $prop = new ImpProposition($prop1, $prop2);
 * $prop1Arg = [];
 * $prop2Arg = [];
 * $prop($prop1Arg, $prop2Arg);
 */
class ImpProposition extends BinaryOperation
{
    /**
     * 論理包含
     *
     * @param boolean $result1
     * @param boolean $result2
     * @return boolean
     */
    protected static function judge(bool $result1, bool $result2) : bool
    {
        return !$result1 || $result2;
    }
}
