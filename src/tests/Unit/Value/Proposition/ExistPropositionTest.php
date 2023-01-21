<?php

namespace Tests\Unit\Domain\Value\Proposition;

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
     * 変数あり
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
    }
}
