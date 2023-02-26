<?php

namespace Tests\Unit\Model\Kripke;

use PHPUnit\Framework\TestCase;
use ModelChecking\Value\Run\Run;
use ModelChecking\Value\State\State;
use ModelChecking\Value\State\DState;
use ModelChecking\Model\Kripke\Kripke;
use ModelChecking\Value\Relation\Relation;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;

class KripkeTest extends TestCase
{
    protected static array $states;
    protected static array $relations;
    protected static array $propositions;
    protected Kripke $model;

    /**
     * dataProviderで上記staticプロパティを使いたいが、
     * setUpBeforeClassより先にdataProviderが呼ばれるのでコンストラクタ時に初期化する。
     *
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

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

    public function setUp() : void
    {
        $this->model = new Kripke(self::$states, self::$relations, self::$propositions);
    }

    public function test_constructor()
    {
        // 参照そのものが一致することを確認する。

        // 状態のチェック
        $this->assertSame(self::$states, $this->model->getStates());

        // 遷移関係のチェック
        $this->assertSame(self::$relations, $this->model->getRelations());

        // ラベル付け関数のチェック
        foreach (self::$states as $state) {
            /** @var DState $state */

            $resultPropositions = $this->model->getPropositionForLabelState($state);
            // x,y,z分の3つが入ってるはず
            $this->assertCount(3, $resultPropositions);

            $expectSData = $state->getData(); // [1,1,1]みたいなのが入ってる
            $expectPropositions = [
                self::$propositions['x=' . $expectSData[0]],
                self::$propositions['y=' . $expectSData[1]],
                self::$propositions['z=' . $expectSData[2]],
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

    /**
      * @dataProvider case_getRuns
      *
      * @param State|null $state
      * @param Run[] $expected
      * @return void
      */
    public function test_getRuns(?State $state, array $expected)
    {
        // 初期状態からの実行が全て取れるかを確認する
        $runs = iterator_to_array($this->model->getRuns($state));

        // countが一致してかつexpectedのrunが全て含まれていればOK
        $this->assertCount(count($expected), $runs);
        foreach ($expected as $expectedRun) {
            // if (!in_array($expectedRun, $runs)) {
            //     var_dump($runs);
            // }
            $this->assertTrue(in_array($expectedRun, $runs));
            
        }
    }
    public function case_getRuns()
    {
        // 1. 実行の開始状態
        // 2. 結果
        return [
            [
                null, // self::$states['1_1_0']
                [
                    new Run([self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0']]),
                ],
            ],
            [
                self::$states['2_1_0'],
                [
                    new Run([self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0']]),
                    new Run([self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0'],self::$states['2_1_0']]),
                    new Run([self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0']]),
                ],
            ],
            [
                self::$states['1_1_1'],
                [
                    new Run([self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1']]),
                    new Run([self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1']]),
                ],
            ],
            [
                self::$states['2_1_1'],
                [
                    new Run([self::$states['2_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1']]),
                    new Run([self::$states['2_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['2_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1']]),
                ],
            ],
            [
                self::$states['1_2_0'],
                [
                    new Run([self::$states['1_2_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0']]),
                    new Run([self::$states['1_2_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0'],self::$states['2_1_0']]),
                    new Run([self::$states['1_2_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0']]),
                ],
            ],
            [
                self::$states['2_2_0'],
                [
                    new Run([self::$states['2_2_0'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['2_2_0'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['2_2_0'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['2_2_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['2_2_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1']]),
                    new Run([self::$states['2_2_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['2_2_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1']]),
                ],
            ],
            [
                self::$states['1_2_1'],
                [
                    new Run([self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1']]),
                    new Run([self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1']]),
                ],
            ],
            [
                self::$states['2_2_1'],
                [
                    new Run([self::$states['2_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['2_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['2_2_1'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0']]),
                    new Run([self::$states['2_2_1'],self::$states['1_1_1'],self::$states['2_2_1'],self::$states['1_1_0'],self::$states['2_1_0'],self::$states['1_1_1'],self::$states['1_2_1'],self::$states['1_1_0']]),
                ],
            ],
        ];
    }
}
