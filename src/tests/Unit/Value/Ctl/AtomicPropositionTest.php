<?php

namespace Tests\Unit\Value\Ctl;

use Tests\Util\TestKripleModel;
use ModelChecking\Value\Logic\Ctl\AtomicProposition;

class AtomicPropositionTest extends CtlPropositionTestCase
{
    /**
     * invokeが動作するかのテスト
     *
     * @return void
     */
    public function test_invoke()
    {
        $prop = new AtomicProposition(TestKripleModel::$propositions['y=2']);

        $this->assertSame(true, $prop($this->model, TestKripleModel::$states['2_2_1']));
        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['1_1_0']));
    }
}
