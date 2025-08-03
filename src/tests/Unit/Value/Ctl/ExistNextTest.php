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
        $atmProp = $this->createAtomicProp('y=2');
        $prop = new ExistNext($atmProp);
        
        $this->assertPropResults($prop, [
            '1_1_1' => true,  // 次状態のいずれかでy=2
            '2_2_0' => false, // 次状態のどれでもy≠2
        ]);
    }
}
