<?php

namespace Tests\Unit\Value\Proposition;

use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Logic\Proposition\NotProposition;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;

class NotPropositionTest extends TestCase
{
    /**
     * invokeが動作するかのテスト
     * 変数なし
     *
     * @return void
     */
    public function test_invoke_noargs()
    {
        $func = function () {
            return true;
        };

        $aProp = new AtomicProposition($func);
        $prop = new NotProposition($aProp);
        $this->assertSame(false, $prop());
    }

    /**
     * invokeが動作するかのテスト
     * 変数あり
     *
     * @return void
     */
    public function test_invoke_args()
    {
        $func = function ($arg1, $arg2) {
            return (bool)$arg1;
        };

        $aProp = new AtomicProposition($func);
        $prop = new NotProposition($aProp);

        $this->assertSame(false, $prop('test', 0));
        $this->assertSame(true, $prop(false, 0));
    }
}
