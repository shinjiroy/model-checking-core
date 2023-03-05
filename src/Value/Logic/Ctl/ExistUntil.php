<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;

/**
 * 実行可能な無限長の実行の内、Untilを満たすものが存在する
 * 
 * for some σ in Run, Until(σ, φ, Ψ)
 */
class ExistUntil extends Until
{
    /**
     * 判定処理の大元
     *
     * @param Kripke $model
     * @param State $state
     * @return boolean
     */
    protected function ctlInvoke(Kripke $model, State $state): bool
    {
        foreach ($model->getRuns($state) as $run) {
            if ($this->until($model, $run)) {
                return true;
            }
        }
        return false;
    }
}
