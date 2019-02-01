<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.logic_opeartor")
 */
class LogicOperator
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\OperatorValue", inversedBy="LogicOperators")
     */
    private $operatorValue;

    /**
     * Set $id
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * Get $id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set $operatorValue
     *
     * @param App\Entity\Main\OperatorValue
     */
    public function setOperatorValue(?OperatorValue $operatorValue)
    {
        $this->operatorValue = $operatorValue;
    }

    /**
     * Get $operatorValue
     *
     * @return App\Entity\Main\OperatorValue
     */
    public function getOperatorValue() : ?OperatorValue
    {
        return $this->operatorValue;
    }
}