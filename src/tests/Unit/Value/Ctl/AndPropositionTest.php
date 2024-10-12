<?php

namespace Tests\Unit\Value\Ctl;

use Tests\Util\TestKripleModel;
use ModelChecking\Value\Logic\Ctl\AndProposition;
use ModelChecking\Value\Logic\Ctl\AtomicProposition;

class AndPropositionTest extends CtlPropositionTestCase
{
    /**
     * invokeが動作するかのテスト
     *
     * @return void
     */
    public function test_invoke()
    {
        $atmProp1 = new AtomicProposition(TestKripleModel::$propositions['x=1']);
        $atmProp2 = new AtomicProposition(TestKripleModel::$propositions['y=2']);
        $prop = new AndProposition($atmProp1, $atmProp2);

        $this->assertSame(true, $prop($this->model, TestKripleModel::$states['1_2_1']));
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['2_2_1']));
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['1_1_1']));
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['2_1_0']));
    }
}
