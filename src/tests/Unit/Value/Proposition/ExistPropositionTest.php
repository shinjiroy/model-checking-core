<?php

namespace Tests\Unit\Value\Proposition;

use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;
use ModelChecking\Value\Logic\Proposition\ExistProposition;

class ExistPropositionTest extends TestCase
{
    /**
     * invokeが動作するかのテスト
     * 変数なし
     *
     * @return void
     */
    public function test_invoke_noarg()
    {
        $func = function () {
            return true;
        };
        $vars = [1,2,3];

        $aProp = new AtomicProposition($func);
        $prop = new ExistProposition($vars, $aProp);
        $this->assertSame(true, $prop());
    }

    /**
     * invokeが動作するかのテスト
     * 変数あり(1個)
     *
     * @return void
     */
    public function test_invoke_arg()
    {
        $func = function ($v) {
            return $v > 2;
        };
        $vars = [1,2,3];

        $aProp = new AtomicProposition($func);
        $prop = new ExistProposition($vars, $aProp);
        $this->assertSame(true, $prop());

        $func = function ($v) {
            return $v > 3;
        };
        $vars = [1,2,3];

        $aProp = new AtomicProposition($func);
        $prop = new ExistProposition($vars, $aProp);
        $this->assertSame(false, $prop());
        $prop = new ExistProposition(array_map(function ($var) {
            return [$var]; // 配列でも行ける
        }, $vars), $aProp);
        $this->assertSame(false, $prop());
    }

    /**
     * invokeが動作するかのテスト
     * 変数あり(2個)
     *
     * @return void
     */
    public function test_invoke_args()
    {
        $func = function ($v1, $v2) {
            return $v1 > 2 && $v2 > 1;
        };
        $vars = [[1,2],[2,3],[3,2]];

        $aProp = new AtomicProposition($func);
        $prop = new ExistProposition($vars, $aProp);
        $this->assertSame(true, $prop());

        $func = function ($v1, $v2) {
            return $v1 > 3 && $v2 > 0;
        };
        $vars = [[1,1],[2,1],[3,1]];

        $aProp = new AtomicProposition($func);
        $prop = new ExistProposition($vars, $aProp);
        $this->assertSame(false, $prop());
    }
}
