<?php

namespace Tests\Util;

use ModelChecking\Value\State\DState;
use ModelChecking\Model\Kripke\Kripke;
use ModelChecking\Value\Relation\Relation;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;

/**
 * KripkeやCTL等のテストで使う
 * 
 * テストコードの__constructでinitializeを呼んでから、setUpでgetModelを呼ぶと良いでしょう。
 */
class TestKripleModel
{
    private static $initialized = false;

    public static array $states;
    public static array $relations;
    public static array $propositions;

    public static function initialize()
    {
        if (self::$initialized) {
            // initialize実行済みなら何もしない
            return;
        }

        self::$initialized = true;

        // ソフトウェア科学基礎p219 並行プログラムP_mutexを同期並行合成した場合のクリプキ構造を参考に
        self::$states = [];
        foreach ([1,2] as $x) {
            foreach ([1,2] as $y) {
                foreach ([0,1] as $z) {
                    self::$states[$x . '_' . $y . '_' . $z] = new DState(
                        $x . '_' . $y . '_' . $z,
                        [$x,$y,$z],
                        $x.$y.$z === '110' ? DState::INIT : DState::DEFAULT
                    );
                }
            }
        }
        self::$relations = [
            new Relation(self::$states['1_1_0'], self::$states['2_1_0']),
            new Relation(self::$states['2_1_0'], self::$states['1_1_1']),
            new Relation(self::$states['1_1_1'], self::$states['1_2_1']),
            new Relation(self::$states['2_1_1'], self::$states['1_2_1']),
            new Relation(self::$states['1_2_0'], self::$states['2_1_0']),
            new Relation(self::$states['2_2_0'], self::$states['1_1_0']),
            new Relation(self::$states['2_2_0'], self::$states['1_1_1']),
            new Relation(self::$states['1_2_1'], self::$states['1_1_0']),
            new Relation(self::$states['2_2_1'], self::$states['1_1_0']),
            new Relation(self::$states['2_2_1'], self::$states['1_1_1']),
            new Relation(self::$states['1_1_1'], self::$states['2_2_1']), // getRunのテストケースをより複雑にするため、遷移を追加してみる
        ];
        // 原子命題の集合はStateに使った変数名(要は$x,$y,$z)とその値の組。
        // 今回で言うと1,2番目には1,2しか入らない。3番目には0,1しか入らないことを縛っている。
        self::$propositions = [];
        foreach ([1,2] as $val) {
            self::$propositions['x=' . $val] = new AtomicProposition(function (DState $state) use ($val) {
                return $state->getData()[0] === $val;
            });
            self::$propositions['y=' . $val] = new AtomicProposition(function (DState $state) use ($val) {
                return $state->getData()[1] === $val;
            });
        }
        foreach ([0,1] as $val) {
            self::$propositions['z=' . $val] = new AtomicProposition(function (DState $state) use ($val) {
                return $state->getData()[2] === $val;
            });
        }
    }

    public static function getModel() : Kripke
    {
        return new Kripke(self::$states, self::$relations, self::$propositions);
    }
}
