<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;

/**
 * ある次の状態で成り立つ
 * 
 * for some s', (s, s') in R and M,s' |= φ
 */
class ExistNext extends CtlProposition
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
        // 現在の状態から遷移可能な状態を取得し、φが成り立つかチェック
        foreach ($model->getRelations() as $relation) {
            if ($relation->getFrom() === $state) {
                if (($this->prop)($model, $relation->getTo())) {
                    return true; // いずれかの次状態でφが成り立てばtrue
                }
            }
        }
        return false; // どの次状態でもφが成り立たなければfalse
    }
}
