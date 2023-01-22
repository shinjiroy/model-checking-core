<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;

/**
 * CTLとしての否定
 */
class NotProposition implements CtlProposition
{
    protected CtlProposition $prop;

    public function __construct(CtlProposition $prop)
    {
        $this->prop = $prop;
    }

    public function __invoke(Kripke $model, State $state): bool
    {
        return !($this->prop)($model, $state);
    }
}
