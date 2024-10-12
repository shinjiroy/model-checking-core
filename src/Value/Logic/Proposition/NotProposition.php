<?php

namespace ModelChecking\Value\Logic\Proposition;

/**
 * 否定(not A)
 */
class NotProposition implements Proposition
{
    protected Proposition $prop;

    /**
     * not $prop
     *
     * @param Proposition $prop
     */
    public function __construct(Proposition $prop)
    {
        $this->prop = $prop;
    }

    public function __invoke(...$args) : bool
    {
        $result1 = ($this->prop)(...$args);
        // 原子命題の時点でbool値を返すことは保証されている
        return !$result1;
    }
}
