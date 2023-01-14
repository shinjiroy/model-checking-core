<?php

namespace Tests\Unit\Domain\Value;

use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Logic\Formula\AtomicFormula;
use ModelChecking\Value\Logic\Formula\AnyFormula;

class AnyFormulaTest extends TestCase
{
    /**
     * invokeが動作するかのテスト
     * 引数なし
     *
     * @return void
     */
    public function test_invoke_noarg()
    {
        $func = function () {
            return true;
        };
        $vars = [1,2,3];

        $aFml = new AtomicFormula($func);
        $fml = new AnyFormula($vars, $aFml);
        $this->assertSame(true, $fml());
    }

    /**
     * invokeが動作するかのテスト
     * 引数あり
     *
     * @return void
     */
    public function test_invoke_arg()
    {
        $func = function ($v) {
            return $v > 0;
        };
        $vars = [1,2,3];

        $aFml = new AtomicFormula($func);
        $fml = new AnyFormula($vars, $aFml);
        $this->assertSame(true, $fml());

        $func = function ($v) {
            return $v > 1;
        };
        $vars = [1,2,3];

        $aFml = new AtomicFormula($func);
        $fml = new AnyFormula($vars, $aFml);
        $this->assertSame(false, $fml());
    }
}
