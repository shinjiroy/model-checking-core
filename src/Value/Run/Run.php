<?php

namespace ModelChecking\Value\Run;

use ModelChecking\Value\State\State;

class Run
{
    /**
     * 実行
     *
     * @var State[]
     */
    protected array $states;

    public function __construct(array $states)
    {
        $this->states = $states;
    }
}
