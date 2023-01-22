<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;

/**
 * CTLの命題
 */
interface CtlProposition
{
    /**
     * 判定処理
     * K,s |= φ
     *
     * @param Kripke $model
     * @param State $state
     * @return boolean
     */
    public function __invoke(Kripke $model, State $state) : bool;
}
