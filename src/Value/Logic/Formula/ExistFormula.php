<?php

namespace ModelChecking\Value\Logic\Formula;

/**
 * 存在
 */
class ExistFormula extends Quantifier
{
    /**
     * 論理式と全ての変数を元に判定する
     *
     * @return boolean
     */
    protected function judge() : bool
    {
        foreach ($this->variables as $variable) {
            if (($this->fml)($variable)) {
                return true;
            }
        }
        return false;
    }
}
