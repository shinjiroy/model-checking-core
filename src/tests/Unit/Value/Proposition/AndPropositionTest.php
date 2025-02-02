<?php

namespace Tests\Unit\Value\Proposition;

use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Logic\Proposition\OrProposition;
use ModelChecking\Value\Logic\Proposition\AndProposition;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;
use ModelChecking\Value\Logic\Proposition\NotProposition;

class AndPropositionTest extends TestCase
{
    /**
     * invokeが動作するかのテスト
     * 変数なし
     *
     * @return void
     */
    public function test_invoke_noargs()
    {
        $func1 = function () {
            return true;
        };
        $func2 = function () {
            return false;
        };

        $prop1 = new AtomicProposition($func1);
        $prop2 = new AtomicProposition($func2);
        $prop = new AndProposition($prop1, $prop2);
        $this->assertSame(false, $prop()); // true and false
    }

    /**
     * invokeが動作するかのテスト
     * 変数片方あり
     *
     * @return void
     */
    public function test_invoke_arg_oneside()
    {
        $func1 = function ($arg1, $arg2) {
            return (bool)$arg1;
        };
        $func2 = function () {
            return true;
        };

        $prop1 = new AtomicProposition($func1);
        $prop2 = new AtomicProposition($func2);
        $prop = new AndProposition($prop1, $prop2);

        $prop1Arg = ['test', 0]; // true and true
        // $prop2Arg = []; // 2番目が変数が無い命題の場合は指定しなくて良い
        $this->assertSame(true, $prop($prop1Arg));
        $prop1Arg = [false, 0]; // false and true
        // $prop2Arg = [];
        $this->assertSame(false, $prop($prop1Arg));
    }

    /**
     * invokeが動作するかのテスト
     * 変数両方あり
     *
     * @return void
     */
    public function test_invoke_arg_both()
    {
        $func1 = function ($arg1, $arg2) {
            return (bool)$arg1;
        };
        $func2 = function ($arg1) {
            return (bool)$arg1;
        };

        $prop1 = new AtomicProposition($func1);
        $prop2 = new AtomicProposition($func2);
        $prop = new AndProposition($prop1, $prop2);

        $prop1Arg = ['test', 0];
        $prop2Arg = ['111'];
        $this->assertSame(true, $prop($prop1Arg, $prop2Arg)); // true and true
        $prop1Arg = [false, 0];
        $prop2Arg = ['test'];
        $this->assertSame(false, $prop($prop1Arg, $prop2Arg)); // false and true
    }
}
