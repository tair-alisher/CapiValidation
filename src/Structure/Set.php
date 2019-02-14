<?php

namespace App\Structure;

class Set
{
    private $values;

    /**
     * @param string '1,2,3'
     * @param string
     */
    public function __construct(string $input, string $type)
    {
        $this->values = explode(',', $input);

        if ($type == 'integer') {
            array_walk($this->values, function (&$element) {
                $element = (int)$element;
            });
        }
    }

    /**
     * Returns values
     *
     * @return array
     */
    public function values(): array
    {
        return $this->values;
    }
}