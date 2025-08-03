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
        $atmProp = $this->createAtomicProp('y=2');
        $prop = new NotProposition($atmProp);
        
        $this->assertPropResults($prop, [
            '2_2_1' => false, // NOT y=2 when y=2
            '1_1_1' => true,  // NOT y=2 when y≠2
        ]);
    }
}
