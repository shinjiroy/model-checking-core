<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;

/**
 * CTLとしての論理積
 * K,s |= φ and Ψ
 */
class AndProposition extends CtlProposition
{
    protected CtlProposition $prop1;
    protected CtlProposition $prop2;

    public function __construct(CtlProposition $prop1, CtlProposition $prop2)
    {
        $this->prop1 = $prop1;
        $this->prop2 = $prop2;
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
        return ($this->prop1)($model, $state) && ($this->prop2)($model, $state);
    }
}
