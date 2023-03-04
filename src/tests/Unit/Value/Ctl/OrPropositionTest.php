<?php

namespace Tests\Unit\Value\Ctl;

use Tests\Util\TestKripleModel;
use ModelChecking\Value\Logic\Ctl\OrProposition;
use ModelChecking\Value\Logic\Ctl\AtomicProposition;

class OrPropositionTest extends CtlPropositionTestCase
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
        $prop = new OrProposition($atmProp1, $atmProp2);

        $this->assertSame(true, $prop($this->model, TestKripleModel::$states['1_1_0']));
        $this->assertSame(true, $prop($this->model, TestKripleModel::$states['2_2_0']));
        $this->assertSame(true, $prop($this->model, TestKripleModel::$states['1_2_0']));
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['2_1_1']));
    }
}
