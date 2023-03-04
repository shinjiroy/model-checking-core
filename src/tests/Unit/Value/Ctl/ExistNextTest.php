<?php

namespace Tests\Unit\Value\Ctl;

use Tests\Util\TestKripleModel;
use ModelChecking\Value\Logic\Ctl\ExistNext;
use ModelChecking\Value\Logic\Ctl\AtomicProposition;

class ExistNextTest extends CtlPropositionTestCase
{
    /**
     * invokeが動作するかのテスト
     *
     * @return void
     */
    public function test_invoke()
    {
        $atmProp = new AtomicProposition(TestKripleModel::$propositions['y=2']);
        $prop = new ExistNext($atmProp);

        $this->assertSame(true, $prop($this->model, TestKripleModel::$states['1_1_1'])); // 1_2_1に遷移する(y=2)
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['1_2_1'])); // 1_1_0に遷移する(y=1)
    }
}
