<?php

namespace ModelChecking\Util;

use InvalidArgumentException;

class ArrayUtil
{
    /**
     * コールバック関数を用いてデータからキーを取得し、データと紐づける
     *
     * @param array $data
     * @param callable $func
     * @return array
     */
    public static function utoMap(array $data, callable $func) : array
    {
        $result = [];
        while ($row = array_pop($data)) {
            $key = $func($row);
            if (!is_string($key) && !is_int($key)) {
                throw new InvalidArgumentException('関数が有効なキーを返しませんでした。');
            }
            $result[$key] = $row;
        }
        return $result;
    }

    /**
     * 配列に含まれるデータが指定のキーについて重複しているかを返す
     *
     * @param array[]|object[] $data
     * @param int|string|array $key
     *      $dataの各行がarrayの時は連想配列のキーとみなします。
     *      $dataの各行がobjectの時はメソッド名とみなします。
     * @return bool
     */
    public static function isDuplicate(array $data, int|string|array $key) : bool
    {
        if (!is_array($key)) {
            $key = [$key];
        }

        $checked = [];
        foreach ($data as $row) {
            $id = self::getId($row, $key);
            if (isset($checked[$id])) {
                return true;
            }
            $checked[$id] = 1;
        }
        return false;
    }

    /**
     * キーに紐づく値を元に文字列のIDを作成する
     * キーが複数ある場合は「val1+val2+」のような文字列をIDとして返す
     *
     * @param array|object $row
     * @param array $keys
     * @return string
     */
    private static function getId(array|object $row, array $keys) : string
    {
        $id = '';
        foreach ($keys as $key) {
            if (is_object($row)) {
                $id .= $row->{$key}();
            } else if (is_array($row)) {
                $id .= $row[$key];
            }
            $id .= '+';
        }
        return $id;
    }
}
