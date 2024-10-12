<?php

namespace Tests\Unit\Model\Kripke;

use PHPUnit\Framework\TestCase;
use Tests\Util\TestKripleModel;
use ModelChecking\Value\Run\Run;
use ModelChecking\Value\State\State;
use ModelChecking\Value\State\DState;
use ModelChecking\Model\Kripke\Kripke;

class KripkeTest extends TestCase
{
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
        TestKripleModel::initialize();
    }

    public function setUp() : void
    {
        $this->model = TestKripleModel::getModel();
    }

    public function test_constructor()
    {
        // 参照そのものが一致することを確認する。

        // 状態のチェック
        $this->assertSame(TestKripleModel::$states, $this->model->getStates());

        // 遷移関係のチェック
        $this->assertSame(TestKripleModel::$relations, $this->model->getRelations());

        // ラベル付け関数のチェック
        foreach (TestKripleModel::$states as $state) {
            /** @var DState $state */

            $resultPropositions = $this->model->getPropositionForLabelState($state);
            // x,y,z分の3つが入ってるはず
            $this->assertCount(3, $resultPropositions);

            $expectSData = $state->getData(); // [1,1,1]みたいなのが入ってる
            $expectPropositions = [
                TestKripleModel::$propositions['x=' . $expectSData[0]],
                TestKripleModel::$propositions['y=' . $expectSData[1]],
                TestKripleModel::$propositions['z=' . $expectSData[2]],
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
            $this->assertTrue(in_array($expectedRun, $runs)); // 参照まで一致していなくて良い
        }
    }
    public function case_getRuns()
    {
        // 1. 実行の開始状態
        // 2. 結果
        return [
            [
                null, // TestKripleModel::$states['1_1_0']
                [
                    new Run([TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0']]),
                ],
            ],
            [
                TestKripleModel::$states['2_1_0'],
                [
                    new Run([TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0']]),
                    new Run([TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0']]),
                    new Run([TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0']]),
                ],
            ],
            [
                TestKripleModel::$states['1_1_1'],
                [
                    new Run([TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1']]),
                    new Run([TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1']]),
                ],
            ],
            [
                TestKripleModel::$states['2_1_1'],
                [
                    new Run([TestKripleModel::$states['2_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1']]),
                    new Run([TestKripleModel::$states['2_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['2_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1']]),
                ],
            ],
            [
                TestKripleModel::$states['1_2_0'],
                [
                    new Run([TestKripleModel::$states['1_2_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0']]),
                    new Run([TestKripleModel::$states['1_2_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0']]),
                    new Run([TestKripleModel::$states['1_2_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0']]),
                ],
            ],
            [
                TestKripleModel::$states['2_2_0'],
                [
                    new Run([TestKripleModel::$states['2_2_0'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['2_2_0'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['2_2_0'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['2_2_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0']]), // 途中221行かずに121に行く
                    new Run([TestKripleModel::$states['2_2_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1']]),
                    new Run([TestKripleModel::$states['2_2_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['2_2_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1']]), // 途中221行って戻って121に行く
                ],
            ],
            [
                TestKripleModel::$states['1_2_1'],
                [
                    new Run([TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1']]),
                    new Run([TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1']]),
                ],
            ],
            [
                TestKripleModel::$states['2_2_1'],
                [
                    new Run([TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0']]),
                    new Run([TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['2_2_1'],TestKripleModel::$states['1_1_0'],TestKripleModel::$states['2_1_0'],TestKripleModel::$states['1_1_1'],TestKripleModel::$states['1_2_1'],TestKripleModel::$states['1_1_0']]),
                ],
            ],
        ];
    }
}
