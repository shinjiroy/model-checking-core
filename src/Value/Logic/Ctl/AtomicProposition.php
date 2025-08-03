<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;
use ModelChecking\Value\Logic\Proposition\AtomicProposition as BasicAtomicProposition;

/**
 * CTLとしての原子命題
 * 
 * p in L(s) (p in PA)
 */
class AtomicProposition extends CtlProposition
{
    protected BasicAtomicProposition $prop;

    public function __construct(BasicAtomicProposition $prop)
    {
        $this->prop = $prop;
    }

    /**
     * 判定処理の大元
     *
     * @param Kripke $model
     * @param State $state
     * @return boolean
     */
    protected function ctlInvoke(Kripke $model, State $state): bool
    {
        $props = $model->getPropositionForLabelState($state);
        return in_array($this->prop, $props, true);
    }
}
