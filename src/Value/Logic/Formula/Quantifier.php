<?php

namespace ModelChecking\Value\Logic\Formula;

/**
 * 量化子付きの論理式
 */
abstract class Quantifier implements Formula
{
    protected array $variables;
    protected Formula $fml;

    /**
     * Q $variables $fml
     *
     * @param array $variables
     * @param Formula $fml
     */
    public function __construct(array $variables, Formula $fml)
    {
        $this->variables = $variables;
        $this->fml = $fml;
    }

    /**
     * 論理式と全ての変数を元に判定する
     *
     * @return boolean
     */
    abstract protected function judge() : bool;

    public function __invoke(...$args) : bool
    {
        // 量化子付きのため、ここで引数を渡す想定ではない。$argsは未使用となる。
        return $this->judge();
    }
}
