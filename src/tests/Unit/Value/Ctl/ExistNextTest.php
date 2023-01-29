<?php

namespace Tests\Unit\Value\Ctl;

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
        $atmProp = new AtomicProposition(self::$propositions[1]); // 0の次、1でのみtrueになるはず
        $prop = new ExistNext($atmProp);

        $this->assertSame(true, $prop($this->model, self::$states[0]));
        $this->assertSame(false, $prop($this->model, self::$states[1]));
    }
}
