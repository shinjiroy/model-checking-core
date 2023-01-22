<?php

namespace Tests\Unit\Value\Ctl;

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
        $prop = new AtomicProposition(self::$propositions[0]);

        $this->assertSame(true, $prop($this->model, self::$states[0]));
        $this->assertSame(false, $prop($this->model, self::$states[1]));
    }
}
