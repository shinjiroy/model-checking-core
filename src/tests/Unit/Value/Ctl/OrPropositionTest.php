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
        $prop = $this->createOrProp('x=1', 'y=2');
        
        $this->assertPropResults($prop, [
            '1_1_0' => true,  // x=1 OR y≠2
            '2_2_1' => true,  // x≠1 OR y=2  
            '1_2_1' => true,  // x=1 OR y=2
            '2_1_1' => false, // x≠1 OR y≠2
        ]);
    }
}
