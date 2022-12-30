<?php

namespace ModelChecking\Value\Transition;

use ModelChecking\Value\State\State;
use ModelChecking\Value\Event\Event;

/**
 * 状態遷移
 */
class Transition
{
    protected State $from;
    protected Event $event;
    protected State $to;

    public function __construct(State $from, Event $event, State $to)
    {
        $this->from = $from;
        $this->event = $event;
        $this->to = $to;
    }
}
