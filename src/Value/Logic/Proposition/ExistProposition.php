<?php

namespace ModelChecking\Value\Logic\Proposition;

/**
 * 存在
 */
class ExistProposition extends Quantifier
{
    /**
     * 命題と全ての変数を元に判定する
     *
     * @return boolean
     */
    protected function judge() : bool
    {
        foreach ($this->vars as $var) {
            if (($this->prop)($var)) {
                return true;
            }
        }
        return false;
    }
}
