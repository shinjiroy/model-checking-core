<?php

namespace ModelChecking\Value\Logic\Ctl;

use ModelChecking\Value\Run\Run;
use ModelChecking\Value\State\State;
use ModelChecking\Model\Kripke\Kripke;

/**
 * Untilを使うCTL
 */
abstract class Until extends CtlProposition
{
    protected CtlProposition $before;
    protected CtlProposition $after;

    /**
     * @param CtlProposition $before ある状態までに成り立つ必要のある命題
     * @param CtlProposition $after ある状態で成り立つ必要のある命題
     */
    public function __construct(CtlProposition $before, CtlProposition $after)
    {
        $this->before = $before;
        $this->after = $after;
    }

    /**
     * $runのある状態では$afterを満たし、それまでは$beforeを満たす
     *
     * @param Kripke $model
     * @param Run $run
     * @return boolean
     */
    protected function until(Kripke $model, Run $run) : bool
    {
        foreach ($run->getStates() as $state) {
            // 先にafterをチェック
            if (($this->after)($model, $state)) {
                // 満たしていたら終了
                return true;
            }

            // afterを満たしていなければbeforeをチェック
            if (!($this->before)($model, $state)) {
                // 1回でも満たさない場合は即終了
                return false;
            }
        }

        // 結局afterを満たす状態が無かった場合は満たさない
        return false;
    }
}
