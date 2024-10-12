<?php

namespace ModelChecking\Value\Logic\Ctl;

use InvalidArgumentException;
use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;
use ModelChecking\Value\Logic\Proposition\Proposition;

/**
 * CTLの命題
 * K,s |= φ
 */
abstract class CtlProposition implements Proposition
{
    /**
     * 判定処理の大元
     *
     * @param Kripke $model
     * @param State $state
     * @return boolean
     */
    abstract protected function ctlInvoke(Kripke $model, State $state) : bool;

    /**
     * 判定処理
     * K,s |= φ
     *
     * @param Kripke $args[0]
     * @param State $args[1]
     * @return boolean
     */
    public function __invoke(...$args) : bool
    {
        $model = $args[0];
        $state = $args[1];
        if ($model instanceof Kripke && $state instanceof State) {
            return $this->ctlInvoke($model, $state);
        }

        throw new InvalidArgumentException('引数は' . Kripke::class . ', ' . State::class . 'です。');
    }
}
