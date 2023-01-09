<?php

namespace ModelChecking\Model\Kripke;

use InvalidArgumentException;
use ModelChecking\Util\ArrayUtil;
use ModelChecking\Value\State\State;
use ModelChecking\Value\Relation\Relation;
use ModelChecking\Value\Logic\Formula\AtomicFormula;

/**
 * クリプキ構造
 */
class Kripke
{
    /**
     * 状態集合
     *
     * @var State[]
     */
    protected array $states = [];
    /**
     * @return State[]
     */
    public function getStates() : array
    {
        return $this->states;
    }

    /**
     * 遷移関係の集合
     *
     * @var Relation[]
     */
    protected array $relations = [];
    /**
     * @return Relation[]
     */
    public function getRelations() : array
    {
        return $this->relations;
    }

    /**
     * ラベル付け関数
     * 各Stateに対してtrueを返すAtomicFormulaの参照が紐づけられる
     * 紐づく物が無い場合は空の配列を返す
     *
     * @var AtomicFormura[][]
     */
    protected array $lblFunction = [];
    /**
     * @var AtomicFormula[]
     */
    public function getFormulaForLabelState(State $state) : array
    {
        if (isset($this->lblFunction[$state->getName()])) {
            return $this->lblFunction[$state->getName()];
        }
        throw new InvalidArgumentException('存在しない状態です。:' . $state->getName());
    }

    /**
     * コンストラクタ
     *
     * @param State[] $states 状態集合
     * @param Relation[] $relations 遷移関係の集合
     * @param AtomicFormula[] $formulas Stateを引数とするAtomicFormulaの集合
     */
    public function __construct(array $states, array $relations, array $formulas)
    {
        if (ArrayUtil::isDuplicate($states, 'getName')) {
            throw new InvalidArgumentException('重複した状態が含まれています。');
        }
        $this->states = $states;
        $this->relations = $relations;

        // ラベル付け関数の構築
        foreach ($states as $state) {
            $this->lblFunction[$state->getName()] = [];
            foreach ($formulas as $formula) {
                if ($formula($state)) {
                    $this->lblFunction[$state->getName()][] = $formula;
                }
            }
        }
    }
}
