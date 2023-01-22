<?php

namespace Tests\Unit\Value\Ctl;

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
        $atmProp = new AtomicProposition(self::$propositions[0]);
        $prop = new NotProposition($atmProp);

        $this->assertSame(false, $prop($this->model, self::$states[0]));
        $this->assertSame(true, $prop($this->model, self::$states[1]));
    }
}
