<?php

namespace ModelChecking\Value\Logic\Proposition;

use Closure;
use TypeError;

/**
 * 原子命題
 */
class AtomicProposition implements Proposition
{
    private Closure $func;

    /**
     * @param callable $func (any) => bool
     */
    public function __construct(callable $func)
    {
        $this->func = $func;
    }

    public function __invoke(...$args) : bool
    {
        $result = ($this->func)(...$args);
        if (!is_bool($result)) {
            throw new TypeError('命題なのにbool値を返していません。');
        }
        return $result;
    }
}
