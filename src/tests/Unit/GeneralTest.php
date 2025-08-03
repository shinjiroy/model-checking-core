<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;

class GeneralTest extends TestCase
{
    /**
     * オブジェクトの等価性を確認する
     *
     * @return void
     */
    public function test_equivalence_object()
    {
        $func1 = function () {
            return true;
        };
        $func2 = function () {
            return true;
        };

        // プロパティの無名関数の参照が異なるが、内容が一致する場合
        $prop1 = new AtomicProposition($func1);
        $prop2 = new AtomicProposition($func2);
        $this->assertEquals($prop1, $prop2);
        $this->assertNotSame($prop1, $prop2);

        // プロパティの無名関数の参照が一致する場合
        $prop1 = new AtomicProposition($func1);
        $prop2 = new AtomicProposition($func1);
        $this->assertEquals($prop1, $prop2);
        $this->assertNotSame($prop1, $prop2);

        // 参照が一致する場合
        $prop1 = new AtomicProposition($func1);
        $prop2 = $prop1;
        $this->assertSame($prop1, $prop2);
    }
}
