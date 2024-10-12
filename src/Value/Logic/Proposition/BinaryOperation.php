<?php

namespace ModelChecking\Value\Logic\Proposition;

/**
 * 二項演算
 */
abstract class BinaryOperation implements Proposition
{
    protected Proposition $prop1;
    protected Proposition $prop2;

    /**
     * $prop1 R $prop2
     *
     * @param Proposition $prop1
     * @param Proposition $prop2
     */
    public function __construct(Proposition $prop1, Proposition $prop2)
    {
        $this->prop1 = $prop1;
        $this->prop2 = $prop2;
    }

    /**
     * 2つの命題の判定結果を元に、最終的な判定結果を返す
     *
     * @param boolean $result1
     * @param boolean $result2
     * @return boolean
     */
    abstract protected static function judge(bool $result1, bool $result2) : bool;

    public function __invoke(...$args) : bool
    {
        $result1 = ($this->prop1)(...($args[0] ?? []));
        $result2 = ($this->prop2)(...($args[1] ?? []));
        // 命題の時点で2つともbool値を返すことは保証されている
        return static::judge($result1, $result2);
    }
}
