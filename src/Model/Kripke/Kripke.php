<?php

namespace ModelChecking\Model\Kripke;

use Generator;
use LogicException;
use InvalidArgumentException;
use ModelChecking\Value\Run\Run;
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

    /**
     * 実行可能な実行を取得する
     * ※ループが発生した時点でストップします。
     *
     * @param State|null $from 開始する状態。指定しない場合は初期状態から。
     *                         指定せず初期状態も無い場合は例外を投げます。
     * @return Generator<Run>
     */
    public function getRuns(?State $from = null) : Generator
    {
        if (is_null($from)) {
            // 指定が無ければ初期状態取得
            $from = current(array_filter($this->states, function (State $state) {
                return $state->getType() === State::INIT;
            })) ?: null;
            if (is_null($from)) {
                throw new LogicException('初期状態を定義するか、開始状態$fromを指定してください。');
            }
        }

        // relationsのインデックスをfromのStateで分類分けする
        $mapFromToRelationsIdxs = [];
        foreach ($this->relations as $index => $relation) {
            if (!isset($mapFromToRelationsIdxs[$relation->getFrom()->getName()])) {
                $mapFromToRelationsIdxs[$relation->getFrom()->getName()] = [];
            }
            $mapFromToRelationsIdxs[$relation->getFrom()->getName()][] = $index;
        }

        // 次々runを作っていく
        foreach ($this->getRunArray($from, $mapFromToRelationsIdxs) as $run) {
            yield new Run($run);
        }
    }

    /**
     * 1つの実行(Stateの配列)を返す
     * ※ループが発生した時点でストップします。
     *
     * @param State $from
     * @param array<string, int[]> $mapFromToRelationsIdxs
     * @return Generator<array>
     */
    protected function getRunArray(State $from, array $mapFromToRelationsIdxs) : Generator
    {
        // 遷移先
        $relationIdxs = $mapFromToRelationsIdxs[$from->getName()] ?? [];

        if (empty($relationIdxs)) {
            // 遷移先が無ければ終わり
            yield [$from];
        }

        foreach ($relationIdxs as $idx => $relationIdx) {
            $next = $this->relations[$relationIdx]->getTo();

            $clonedMapFromToRelationsIdxs = $mapFromToRelationsIdxs;
            // この時、一度した遷移を消した物を渡すようにする。
            unset($clonedMapFromToRelationsIdxs[$from->getName()][$idx]);

            // 次移行の遷移を確認する
            foreach ($this->getRunArray($next, $clonedMapFromToRelationsIdxs) as $run) {
                yield array_merge([$from], $run);
            }
        }
    }
}
