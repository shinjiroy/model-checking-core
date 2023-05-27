<?php

namespace Tests\Unit\Util;

use PHPUnit\Framework\TestCase;
use stdClass;
use ModelChecking\Util\ArrayUtil;
use ModelChecking\Value\State\State;

class ArrayUtilTest extends TestCase
{
    /**
     * utoMapのテスト
     * 
     * @dataProvider case_utoMap
     *
     * @param array $data
     * @param callable $func
     * @param array $expected
     * @return void
     */
    public function test_utoMap(array $data, callable $func, array $expected)
    {
        $result = ArrayUtil::utoMap($data, $func);
        foreach ($expected as $expectedKey => $expectedRow) {
            // 想定通りの値がキーとしてデータと紐づいているか
            $this->assertSame($expectedRow, $result[$expectedKey]);
        }
    }
    public function case_utoMap()
    {
        return [
            // 配列
            [
                [
                    ['key' => 'key1', 'val' => 'val1'],
                    ['key' => 'key2', 'val' => 'val2'],
                    ['key' => 'key3', 'val' => 'val3'],
                ],
                function (array $row) {return $row['key'];},
                [
                    'key1' => ['key' => 'key1', 'val' => 'val1'],
                    'key2' => ['key' => 'key2', 'val' => 'val2'],
                    'key3' => ['key' => 'key3', 'val' => 'val3'],
                ]
            ],
            // オブジェクト
            [
                [
                    $obj1 = (object) ['key' => 'key1', 'val' => 'val1'],
                    $obj2 = (object) ['key' => 'key2', 'val' => 'val2'],
                    $obj3 = (object) ['key' => 'key3', 'val' => 'val3'],
                ],
                function (stdClass $row) {return $row->key;},
                [
                    'key1' => $obj1,
                    'key2' => $obj2,
                    'key3' => $obj3,
                ]
            ],
        ];
    }

    /**
     * isDuplicateのテスト
     * 
     * @dataProvider case_isDuplicate
     *
     * @param array $data
     * @param string|array $key
     * @param boolean $expected
     * @return void
     */
    public function test_isDuplicate(array $data, string|array $key, bool $expected)
    {
        $this->assertSame($expected, ArrayUtil::isDuplicate($data, $key));
    }
    public function case_isDuplicate()
    {
        // 1.データ配列
        // 2.重複とみなすためのキー
        // 3.結果
        return [
            // 配列の配列、キー1つ
            [
                [
                    ['key1' => 'val11', 'key2' => 'val21'],
                    ['key1' => 'val12', 'key2' => 'val22'],
                    ['key1' => 'val13', 'key2' => 'val23'],
                ],
                'key1',
                false
            ],
            [
                [
                    ['key1' => 'val11', 'key2' => 'val21'],
                    ['key1' => 'val11', 'key2' => 'val22'],
                    ['key1' => 'val13', 'key2' => 'val23'],
                ],
                'key1',
                true
            ],
            // 配列の配列、キー複数
            [
                [
                    ['key1' => 'val11', 'key2' => 'val21'],
                    ['key1' => 'val12', 'key2' => 'val22'],
                    ['key1' => 'val13', 'key2' => 'val23'],
                ],
                ['key1', 'key2'],
                false
            ],
            [
                [
                    ['key1' => 'val11', 'key2' => 'val21'],
                    ['key1' => 'val12', 'key2' => 'val21'],
                    ['key1' => 'val13', 'key2' => 'val23'],
                ],
                ['key1', 'key2'],
                false
            ],
            [
                [
                    ['key1' => 'val11', 'key2' => 'val21'],
                    ['key1' => 'val12', 'key2' => 'val21'],
                    ['key1' => 'val12', 'key2' => 'val21'],
                ],
                ['key1', 'key2'],
                true
            ],
            // オブジェクトの配列
            [
                [
                    new State('0_0'),
                    new State('0_1'),
                    new State('1_0'),
                ],
                'getName',
                false
            ],
            [
                [
                    new State('0_0'),
                    new State('0_1'),
                    new State('0_1'),
                ],
                'getName',
                true
            ],
        ];
    }
}
