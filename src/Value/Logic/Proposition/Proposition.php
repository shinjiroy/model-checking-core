<?php

namespace ModelChecking\Value\Logic\Proposition;

/**
 * 命題
 * ※論理式も兼ねる
 */
interface Proposition
{
    public function __invoke(...$args) : bool;
}
