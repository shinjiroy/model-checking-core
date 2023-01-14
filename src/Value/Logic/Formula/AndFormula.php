<?php

namespace ModelChecking\Value\Logic\Formula;

/**
 * 論理和(A and B)
 * 
 * $fml = new AndFormula($fml1, $fml2);
 * $fml1Arg = [];
 * $fml2Arg = [];
 * $fml($fml1Arg, $fml2Arg);
 */
class AndFormula extends BinaryOperation
{
    /**
     * AND演算
     *
     * @param boolean $result1
     * @param boolean $result2
     * @return boolean
     */
    protected static function judge(bool $result1, bool $result2) : bool
    {
        return $result1 && $result2;
    }
}
