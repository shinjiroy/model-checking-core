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
        $prop = $this->createAtomicProp('y=2');
        
        $this->assertPropResults($prop, [
            '2_2_1' => true,  // y=2
            '1_2_1' => true,  // y=2
            '1_1_1' => false, // y≠2
        ]);
    }
}
