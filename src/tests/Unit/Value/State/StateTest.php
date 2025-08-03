<?php

namespace Tests\Unit\Value\State;

use PHPUnit\Framework\TestCase;
use ModelChecking\Value\State\State;

class StateTest extends TestCase
{
    /**
     * equals メソッドのテスト
     *
     * @return void
     */
    public function test_equals()
    {
        // 同じ name と type の State オブジェクト
        $state1 = new State('test', State::DEFAULT);
        $state2 = new State('test', State::DEFAULT);
        $this->assertTrue($state1->equals($state2));

        // 異なる name の State オブジェクト
        $state3 = new State('different', State::DEFAULT);
        $this->assertFalse($state1->equals($state3));

        // 異なる type の State オブジェクト
        $state4 = new State('test', State::INIT);
        $this->assertFalse($state1->equals($state4));

        // name も type も異なる State オブジェクト
        $state5 = new State('different', State::FINAL);
        $this->assertFalse($state1->equals($state5));
    }

    /**
     * equals メソッドで特別な値のテスト
     *
     * @return void
     */
    public function test_equals_special_values()
    {
        // 空文字列の name
        $state1 = new State('', State::DEFAULT);
        $state2 = new State('', State::DEFAULT);
        $this->assertTrue($state1->equals($state2));

        // 自分自身との比較
        $state3 = new State('self', State::INIT);
        $this->assertTrue($state3->equals($state3));

        // 全種類の type の組み合わせテスト
        $types = [State::DEFAULT, State::INIT, State::FINAL];
        foreach ($types as $type1) {
            foreach ($types as $type2) {
                $stateA = new State('same_name', $type1);
                $stateB = new State('same_name', $type2);
                if ($type1 === $type2) {
                    $this->assertTrue($stateA->equals($stateB), "Type {$type1} should equal {$type2}");
                } else {
                    $this->assertFalse($stateA->equals($stateB), "Type {$type1} should not equal {$type2}");
                }
            }
        }
    }
}