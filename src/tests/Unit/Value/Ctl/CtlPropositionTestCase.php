<?php

namespace Tests\Unit\Value\Ctl;

use PHPUnit\Framework\TestCase;
use Tests\Util\TestKripleModel;
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
}
