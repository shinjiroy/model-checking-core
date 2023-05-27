<?php

namespace ModelChecking\Value\Logic\Proposition;

/**
 * 全称
 * for all x P(x)
 */
class AllProposition extends Quantifier
{
    /**
     * 命題と全ての変数を元に判定する
     *
     * @return boolean
     */
    protected function judge() : bool
    {
        foreach ($this->vars as $var) {
            // 配列の時は変数が複数とみなす
            $result = is_array($var) ? ($this->prop)(...$var) : ($this->prop)($var);
            if (!$result) {
                return false;
            }
        }
        return true;
    }
}
