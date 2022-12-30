<?php

namespace Domain\Model\Kripke;

use Domain\Value\State\State;
use Domain\Value\Relation\Relation;

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
     * 遷移関係
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

    protected array 
}
