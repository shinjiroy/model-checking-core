<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;

/**
 * CTLとしての否定
 * not (K,s |= φ)
 */
class NotProposition extends CtlProposition
{
    protected CtlProposition $prop;

    public function __construct(CtlProposition $prop)
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
        return !($this->prop)($model, $state);
    }
}
