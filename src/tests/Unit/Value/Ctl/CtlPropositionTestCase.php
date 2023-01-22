<?php

namespace Tests\Unit\Value\Ctl;

use ModelChecking\Model\Kripke\Kripke;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;
use ModelChecking\Value\Relation\Relation;
use ModelChecking\Value\State\State;
use PHPUnit\Framework\TestCase;

class CtlPropositionTestCase extends TestCase
{
    protected static array $states;
    protected static array $relations;
    protected static array $propositions;
    protected Kripke $model;

    public static function setUpBeforeClass() : void
    {
        self::$states = [
            new State('0'),
            new State('1'),
            new State('2'),
            new State('3'),
        ];
        self::$relations = [
            new Relation(self::$states[0], self::$states[1]),
            new Relation(self::$states[1], self::$states[2]),
            new Relation(self::$states[3], self::$states[2]),
        ];
        self::$propositions = [
            new AtomicProposition(function (State $state) {
                return $state->getName() === '0';
            }),
            new AtomicProposition(function (State $state) {
                return $state->getName() === '1';
            }),
            new AtomicProposition(function (State $state) {
                return $state->getName() === '2';
            }),
            new AtomicProposition(function (State $state) {
                return $state->getName() === '3';
            }),
        ];
    }

    public function setUp() : void
    {
        $this->model = new Kripke(self::$states, self::$relations, self::$propositions);
    }
}
