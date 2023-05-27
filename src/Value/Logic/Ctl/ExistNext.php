<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;
use ModelChecking\Value\Relation\Relation;
use ModelChecking\Value\Logic\Proposition\AndProposition;
use ModelChecking\Value\Logic\Proposition\ExistProposition;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;

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
        // 定義通りに状態s'に存在量化子をつけると効率が悪いため、遷移につけるものとする
        $prop = new AndProposition(
            new AtomicProposition(function (Relation $relation) use ($state) {
                return $relation->getFrom() === $state;
            }),
            new AtomicProposition(function (Relation $relation) use ($model) {
                return ($this->prop)($model, $relation->getTo());
            })
        );
        $vars = array_map(function (Relation $relation) {
            return [
                [$relation], // andの左側の引数
                [$relation], // andの右側の引数
            ];
        }, $model->getRelations());
        return (new ExistProposition($vars, $prop))();
    }
}
