<?php

namespace ModelChecking\Value\State;

/**
 * データ付きの状態
 */
class DState extends State
{
    protected mixed $data;
    public function getData() : mixed
    {
        return $this->data;
    }

    public function __construct(string $name, int $type = self::DEFAULT, mixed $data)
    {
        parent::__construct($name, $type);
        $this->data = $data;
    }
}
