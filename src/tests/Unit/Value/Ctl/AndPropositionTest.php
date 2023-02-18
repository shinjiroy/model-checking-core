<?php

namespace Tests\Unit\Value\Ctl;

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
        $atmProp1 = new AtomicProposition(self::$propositions[0]);
        $atmProp2 = new AtomicProposition(self::$propositions[0]);
        $prop = new AndProposition($atmProp1, $atmProp2);

        $this->assertSame(true, $prop($this->model, self::$states[0]));
        $this->assertSame(false, $prop($this->model, self::$states[1]));
        $this->assertSame(false, $prop($this->model, self::$states[2]));
    }
}
