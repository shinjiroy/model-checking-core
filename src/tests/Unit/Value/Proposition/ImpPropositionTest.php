<?php

namespace Tests\Unit\Value\Proposition;

use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Logic\Proposition\ImpProposition;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;

class ImpPropositionTest extends TestCase
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
        $prop = new ImpProposition($prop1, $prop2);
        $this->assertSame(false, $prop()); // true => false
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
            return false;
        };

        $prop1 = new AtomicProposition($func1);
        $prop2 = new AtomicProposition($func2);
        $prop = new ImpProposition($prop1, $prop2);

        $prop1Arg = ['test', 0];
        // $prop2Arg = []; // 2番目が変数が無い命題の場合は指定しなくて良い
        $this->assertSame(false, $prop($prop1Arg)); // true => false
        $prop1Arg = [false, 0];
        // $prop2Arg = [];
        $this->assertSame(true, $prop($prop1Arg)); // false => false
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
        $prop = new ImpProposition($prop1, $prop2);

        $prop1Arg = ['test', 0];
        $prop2Arg = ['111'];
        $this->assertSame(true, $prop($prop1Arg, $prop2Arg)); // true => true
        $prop1Arg = [false, 0];
        $prop2Arg = [''];
        $this->assertSame(true, $prop($prop1Arg, $prop2Arg)); // false => false
    }
}
