<?php

namespace Tests\Unit\Value\Ctl;

use Tests\Util\TestKripleModel;
use ModelChecking\Value\Logic\Ctl\ExistUntil;
use ModelChecking\Value\Logic\Ctl\OrProposition;
use ModelChecking\Value\Logic\Ctl\AndProposition;
use ModelChecking\Value\Logic\Ctl\AtomicProposition;

class ExistUntilTest extends CtlPropositionTestCase
{
    /**
     * invokeが動作するかのテスト
     *
     * @return void
     */
    public function test_invoke()
    {
        $before = new AtomicProposition(TestKripleModel::$propositions['x=1']);
        $after = new AndProposition(
            new AtomicProposition(TestKripleModel::$propositions['x=2']),
            new AtomicProposition(TestKripleModel::$propositions['y=2'])
        );
        $prop = new ExistUntil($before, $after);

        // ※KripkeTestのcase_getRunsを参考に
        $this->assertSame(true, $prop($this->model, TestKripleModel::$states['1_1_1']));
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['1_2_1'])); // 途中で両方満たさなくなる

        $before = new OrProposition(
            new AtomicProposition(TestKripleModel::$propositions['y=1']),
            new AtomicProposition(TestKripleModel::$propositions['y=2'])
        ); // 常にtrue
        $after = new AndProposition(
            new AtomicProposition(TestKripleModel::$propositions['y=1']),
            new AtomicProposition(TestKripleModel::$propositions['y=2'])
        ); // 常にfalse
        $prop = new ExistUntil($before, $after);
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['2_2_0'])); // 最後までafterを満たすものが無い
    }
}
