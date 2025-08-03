<?php

namespace Tests\Unit\Value\Ctl;

use PHPUnit\Framework\TestCase;
use Tests\Util\TestKripleModel;
use ModelChecking\Value\Logic\Ctl\AtomicProposition;
use ModelChecking\Value\Logic\Ctl\AndProposition;
use ModelChecking\Value\Logic\Ctl\OrProposition;
use ModelChecking\Value\Logic\Ctl\CtlProposition;
use ModelChecking\Model\Kripke\Kripke;

class CtlPropositionTestCase extends TestCase
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

    /**
     * 指定されたキーでAtomicPropositionを作成するヘルパー
     *
     * @param string $key 命題のキー (例: 'x=1', 'y=2')
     * @return AtomicProposition
     */
    protected function createAtomicProp(string $key): AtomicProposition
    {
        return new AtomicProposition(TestKripleModel::$propositions[$key]);
    }

    /**
     * AND命題を作成するヘルパー
     *
     * @param string $key1 最初の命題のキー
     * @param string $key2 二番目の命題のキー
     * @return AndProposition
     */
    protected function createAndProp(string $key1, string $key2): AndProposition
    {
        return new AndProposition(
            $this->createAtomicProp($key1),
            $this->createAtomicProp($key2)
        );
    }

    /**
     * OR命題を作成するヘルパー
     *
     * @param string $key1 最初の命題のキー
     * @param string $key2 二番目の命題のキー
     * @return OrProposition
     */
    protected function createOrProp(string $key1, string $key2): OrProposition
    {
        return new OrProposition(
            $this->createAtomicProp($key1),
            $this->createAtomicProp($key2)
        );
    }

    /**
     * 指定した状態でCTL命題をテストするヘルパー
     *
     * @param CtlProposition $prop CTL命題
     * @param string $stateKey 状態キー (例: '1_1_1', '2_2_1')
     * @return bool 評価結果
     */
    protected function evaluateProp(CtlProposition $prop, string $stateKey): bool
    {
        return $prop($this->model, TestKripleModel::$states[$stateKey]);
    }

    /**
     * 命題とテストケースのペアを使ったアサーションヘルパー
     *
     * @param CtlProposition $prop CTL命題
     * @param array $testCases ['state_key' => expected_bool, ...]
     */
    protected function assertPropResults(CtlProposition $prop, array $testCases): void
    {
        foreach ($testCases as $stateKey => $expected) {
            $result = $this->evaluateProp($prop, $stateKey);
            $this->assertSame($expected, $result, "Failed for state {$stateKey}");
        }
    }
}
