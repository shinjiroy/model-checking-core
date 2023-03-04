<?php

namespace Tests\Unit\Value\Ctl;

use Tests\Util\TestKripleModel;
use ModelChecking\Value\Logic\Ctl\NotProposition;
use ModelChecking\Value\Logic\Ctl\AtomicProposition;

class NotPropositionTest extends CtlPropositionTestCase
{
    /**
     * invokeが動作するかのテスト
     *
     * @return void
     */
    public function test_invoke()
    {
        $atmProp = new AtomicProposition(TestKripleModel::$propositions['y=2']);
        $prop = new NotProposition($atmProp);

        $this->assertSame(false, $prop($this->model, TestKripleModel::$states['2_2_1']));
        $this->assertSame(true, $prop($this->model, TestKripleModel::$states['2_1_1']));
    }
}
