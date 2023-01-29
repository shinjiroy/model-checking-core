<?php

namespace Tests\Unit\Value\Proposition;

use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Logic\Proposition\OrProposition;
use ModelChecking\Value\Logic\Proposition\AndProposition;
use ModelChecking\Value\Logic\Proposition\ImpProposition;
use ModelChecking\Value\Logic\Proposition\NotProposition;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;

/**
 * 各論理式を複合的に組み合わせた時の確認を行う
 */
class PropositionTest extends TestCase
{
    /**
     * ドモルガンの法則が成り立つこと
     * not (A and B) iff (not A) or (not B)
     *
     * @return void
     */
    public function test_de_morgan()
    {
        $func1 = function ($arg1) {
            return (bool)$arg1;
        };
        $func2 = function ($arg1) {
            return (bool)$arg1;
        };

        $prop1 = new AtomicProposition($func1);
        $prop2 = new AtomicProposition($func2);

        $left = new NotProposition(new AndProposition($prop1, $prop2));
        $right = new OrProposition(new NotProposition($prop1), new NotProposition($prop2));

        $prop1Arg = [true];
        $prop2Arg = [true];
        $this->assertSame(false, $right($prop1Arg, $prop2Arg));
        $this->assertSame(false, $left($prop1Arg, $prop2Arg));
        $prop1Arg = [true];
        $prop2Arg = [false];
        $this->assertSame(true, $right($prop1Arg, $prop2Arg));
        $this->assertSame(true, $left($prop1Arg, $prop2Arg));
        $prop1Arg = [false];
        $prop2Arg = [true];
        $this->assertSame(true, $right($prop1Arg, $prop2Arg));
        $this->assertSame(true, $left($prop1Arg, $prop2Arg));
        $prop1Arg = [false];
        $prop2Arg = [false];
        $this->assertSame(true, $right($prop1Arg, $prop2Arg));
        $this->assertSame(true, $left($prop1Arg, $prop2Arg));
    }

    /**
     * 論理包含の書き換え
     * A => B iff not A or B
     *
     * @return void
     */
    public function test_implication_equivalence()
    {
        $func1 = function ($arg1) {
            return (bool)$arg1;
        };
        $func2 = function ($arg1) {
            return (bool)$arg1;
        };

        $prop1 = new AtomicProposition($func1);
        $prop2 = new AtomicProposition($func2);

        $left = new ImpProposition($prop1, $prop2);
        $right = new OrProposition(new NotProposition($prop1), $prop2);

        $prop1Arg = [true];
        $prop2Arg = [true];
        $this->assertSame(true, $right($prop1Arg, $prop2Arg));
        $this->assertSame(true, $left($prop1Arg, $prop2Arg));
        $prop1Arg = [true];
        $prop2Arg = [false];
        $this->assertSame(false, $right($prop1Arg, $prop2Arg));
        $this->assertSame(false, $left($prop1Arg, $prop2Arg));
        $prop1Arg = [false];
        $prop2Arg = [true];
        $this->assertSame(true, $right($prop1Arg, $prop2Arg));
        $this->assertSame(true, $left($prop1Arg, $prop2Arg));
        $prop1Arg = [false];
        $prop2Arg = [false];
        $this->assertSame(true, $right($prop1Arg, $prop2Arg));
        $this->assertSame(true, $left($prop1Arg, $prop2Arg));
    }
}
