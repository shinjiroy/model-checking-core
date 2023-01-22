<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;
use ModelChecking\Value\Logic\Proposition\AtomicProposition as FormalAtmProposition;

/**
 * CTLとしての原子命題
 */
class AtomicProposition implements CtlProposition
{
    protected FormalAtmProposition $prop;

    public function __construct(FormalAtmProposition $prop)
    {
        $this->prop = $prop;
    }

    public function __invoke(Kripke $model, State $state): bool
    {
        $props = $model->getPropositionForLabelState($state);
        return in_array($this->prop, $props, true);
    }
}
