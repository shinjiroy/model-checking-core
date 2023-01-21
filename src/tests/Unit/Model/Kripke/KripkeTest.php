<?php

namespace Tests\Unit\Domain\Model\Kripke;

use ModelChecking\Model\Kripke\Kripke;
use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;
use ModelChecking\Value\State\DState;
use ModelChecking\Value\Relation\Relation;

class KripkeTest extends TestCase
{
    public function test_constructor()
    {
        // ソフトウェア科学基礎p219 並行プログラムP_mutexを同期並行合成した場合のクリプキ構造
        $states = [];
        foreach ([1,2] as $x) {
            foreach ([1,2] as $y) {
                foreach ([0,1] as $z) {
                    $states[$x . '_' . $y . '_' . $z] = new DState(
                        $x . '_' . $y . '_' . $z,
                        [$x,$y,$z],
                        $x.$y.$z === '110' ? DState::INIT : DState::DEFAULT // 一応合わせたが、初期状態とかは特に意識しない。
                    );
                }
            }
        }
        $relations = [
            new Relation($states['1_1_0'], $states['2_1_0']),
            new Relation($states['2_1_0'], $states['1_1_1']),
            new Relation($states['1_1_1'], $states['1_2_1']),
            new Relation($states['2_1_1'], $states['1_2_1']),
            new Relation($states['1_2_0'], $states['2_1_0']),
            new Relation($states['2_2_0'], $states['1_1_0']),
            new Relation($states['2_2_0'], $states['1_1_1']),
            new Relation($states['1_2_1'], $states['1_1_0']),
            new Relation($states['2_2_1'], $states['1_1_0']),
            new Relation($states['2_2_1'], $states['1_1_1']),
        ];
        // 原子命題の集合はStateに使った変数名(要は$x,$y,$z)とその値の組。
        // 今回で言うと1,2番目には1,2しか入らない。3番目には0,1しか入らないことを縛っている。
        $propositions = [];
        foreach ([1,2] as $val) {
            $propositions['x=' . $val] = new AtomicProposition(function (DState $state) use ($val) {
                return $state->getData()[0] === $val;
            });
            $propositions['y=' . $val] = new AtomicProposition(function (DState $state) use ($val) {
                return $state->getData()[1] === $val;
            });
        }
        foreach ([0,1] as $val) {
            $propositions['z=' . $val] = new AtomicProposition(function (DState $state) use ($val) {
                return $state->getData()[2] === $val;
            });
        }
        $result = new Kripke($states, $relations, $propositions);

        // 参照そのものが一致することを確認する。

        // 状態のチェック
        $this->assertSame($states, $result->getStates());

        // 遷移関係のチェック
        $this->assertSame($relations, $result->getRelations());

        // ラベル付け関数のチェック
        foreach ($states as $state) {
            /** @var DState $state */

            $resultPropositions = $result->getPropositionForLabelState($state);
            // x,y,z分の3つが入ってるはず
            $this->assertCount(3, $resultPropositions);

            $expectSData = $state->getData(); // [1,1,1]みたいなのが入ってる
            $expectPropositions = [
                $propositions['x=' . $expectSData[0]],
                $propositions['y=' . $expectSData[1]],
                $propositions['z=' . $expectSData[2]],
            ];
            // x,y,zに対応するAtomicPropositionが入ってるはず
            // 出来れば$this->assertCount(3, array_intersect($resultPropositions, $expectPropositions));で確認したかった
            foreach ($expectPropositions as $expectProposition) {
                $exists = false;
                foreach ($resultPropositions as $resultProposition) {
                    if ($expectProposition === $resultProposition) {
                        // 参照が一致している物を含めばOK
                        $exists = true;
                        break;
                    }
                }
                $this->assertTrue($exists);
            }
        }
    }
}
