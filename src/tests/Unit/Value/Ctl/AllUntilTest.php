<?php

namespace Tests\Unit\Value\Ctl;

use Tests\Util\TestKripleModel;
use ModelChecking\Value\Logic\Ctl\AllUntil;
use ModelChecking\Value\Logic\Ctl\AndProposition;
use ModelChecking\Value\Logic\Ctl\AtomicProposition;
use ModelChecking\Value\Logic\Ctl\OrProposition;

class AllUntilTest extends CtlPropositionTestCase
{
    /**
     * invokeが動作するかのテスト
     *
     * @return void
     */
    public function test_invoke()
    {
        $before = new AtomicProposition(TestKripleModel::$propositions['y=1']);
        $after = new AndProposition(
            new AtomicProposition(TestKripleModel::$propositions['y=2']),
            new AtomicProposition(TestKripleModel::$propositions['z=1'])
        );
        $prop = new AllUntil($before, $after);

        // ※KripkeTestのcase_getRunsを参考に
        $this->assertSame(true, $prop($this->model, TestKripleModel::$states['1_1_0']));
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['2_2_0'])); // 初手両方満たさない

        $before = new OrProposition(
            new AtomicProposition(TestKripleModel::$propositions['y=1']),
            new AtomicProposition(TestKripleModel::$propositions['y=2'])
        ); // 常にtrue
        $after = new OrProposition(
            new AndProposition(
                new AndProposition(
                    new AtomicProposition(TestKripleModel::$propositions['x=2']),
                    new AtomicProposition(TestKripleModel::$propositions['y=2'])
                ),
                new AtomicProposition(TestKripleModel::$propositions['z=1'])
            ), // 一部のrunはこれを満たす。(2_2_1)
            new AndProposition(
                new AtomicProposition(TestKripleModel::$propositions['y=1']),
                new AtomicProposition(TestKripleModel::$propositions['y=2'])
            ) // 常にfalse
        );
        $prop = new AllUntil($before, $after);
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['2_2_0'])); // 最後までafterを満たすものが無い
    }
}
