<?php

namespace App\Structure;

class Range
{
    private $from;
    private $to;

    public function __construct(string $input)
    {
        $values = explode('-', $input);

        $this->from = (float)$values[0];
        $this->to = (float)$values[1];
    }

    /**
     * Returns $from value
     *
     * @return float
     */
    public function from(): float
    {
        return $this->from;
    }

    /**
     * Returns $to value
     *
     * @return float
     */
    public function to(): float
    {
        return $this->to;
    }
}