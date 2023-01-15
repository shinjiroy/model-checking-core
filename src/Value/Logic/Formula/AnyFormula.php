<?php

namespace ModelChecking\Value\Logic\Formula;

/**
 * 任意
 */
class AnyFormula extends Quantifier
{
    /**
     * 論理式と全ての変数を元に判定する
     *
     * @return boolean
     */
    protected function judge() : bool
    {
        foreach ($this->vars as $var) {
            if (!($this->fml)($var)) {
                return false;
            }
        }
        return true;
    }
}
