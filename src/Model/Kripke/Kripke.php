<?php

namespace ModelChecking\Model\Kripke;

use InvalidArgumentException;
use ModelChecking\Util\ArrayUtil;
use ModelChecking\Value\State\State;
use ModelChecking\Value\Relation\Relation;
use ModelChecking\Value\Logic\Ctl\CtlProposition;
use ModelChecking\Value\Logic\Proposition\AtomicProposition;

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
     * 各Stateに対してtrueを返すAtomicPropositionの参照が紐づけられる
     * 紐づく物が無い場合は空の配列を返す
     *
     * @var AtomicProposition[][]
     */
    protected array $lblFunction = [];
    /**
     * @var AtomicProposition[]
     */
    public function getPropositionForLabelState(State $state) : array
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
     * @param AtomicProposition[] $propositions Stateを引数とするAtomicPropositionの集合
     *      状態毎の振り分けはコンストラクタ内で行います。
     *      ※本来原子命題にそのような条件は無く、ラベル付け関数も外で定義され、直接ここに渡されるべきだろうと思います。
     */
    public function __construct(array $states, array $relations, array $propositions)
    {
        if (ArrayUtil::isDuplicate($states, 'getName')) {
            throw new InvalidArgumentException('重複した状態が含まれています。');
        }
        $this->states = $states;
        $this->relations = $relations;

        // ラベル付け関数の構築
        foreach ($states as $state) {
            $this->lblFunction[$state->getName()] = [];
            foreach ($propositions as $proposition) {
                if ($proposition($state)) {
                    $this->lblFunction[$state->getName()][] = $proposition;
                }
            }
        }
    }

    /**
     * 状態stateがCTL式propを満たすか
     *
     * @param State $state
     * @param CtlProposition $prop
     * @return boolean
     */
    public function satisfy(State $state, CtlProposition $prop) : bool
    {
        if (!in_array($state, $this->states, true)) {
            throw new InvalidArgumentException('存在しない状態です。');
        }
        return $prop($this, $state);
    }
}
