<?php

namespace ModelChecking\Value\Logic\Formula;

/**
 * 論理式
 * ※判定までするので、命題です
 */
interface Formula
{
    public function __invoke(...$args) : bool;
}
