<?php

namespace ModelChecking\Value\Logic\Proposition;

/**
 * 量化子付きの命題
 */
abstract class Quantifier implements Proposition
{
    protected array $vars;
    protected Proposition $prop;

    /**
     * Q $variables $prop
     *
     * @param array $vars
     * @param Proposition $prop
     */
    public function __construct(array $vars, Proposition $prop)
    {
        $this->vars = $vars;
        $this->prop = $prop;
    }

    /**
     * 命題と全ての変数を元に判定する
     *
     * @return boolean
     */
    abstract protected function judge() : bool;

    public function __invoke(...$args) : bool
    {
        // 量化子付きのため、ここで変数に引数を渡す想定ではない。$argsは未使用となる。
        return $this->judge();
    }
}
