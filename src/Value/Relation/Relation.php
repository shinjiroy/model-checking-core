<?php

namespace ModelChecking\Value\Relation;

use ModelChecking\Value\State\State;

/**
 * 遷移関係
 */
class Relation
{
    protected State $from;
    public function getFrom() : State
    {
        return $this->from;
    }

    protected State $to;
    public function getTo() : State
    {
        return $this->to;
    }

    public function __construct(State $from, State $to)
    {
        $this->from = $from;
        $this->to = $to;
    }
}
