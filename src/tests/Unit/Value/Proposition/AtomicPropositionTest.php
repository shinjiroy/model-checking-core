<?php

namespace Tests\Unit\Value\Proposition;

use TypeError;
use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;

class AtomicPropositionTest extends TestCase
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

        $prop = new AtomicProposition($func);
        $this->assertSame(true, $prop());
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

        $prop = new AtomicProposition($func);
        $this->assertSame(true, $prop('test', 0));
        $this->assertSame(false, $prop(false, 0));
    }

    /**
     * bool値以外を返す時エラーとなることのテスト
     *
     * @return void
     */
    public function test_notbool()
    {
        $func = function () {
            return 1;
        };
        $this->expectException(TypeError::class);

        $prop = new AtomicProposition($func);
        $prop();
    }
}
