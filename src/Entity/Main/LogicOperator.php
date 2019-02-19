<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.logic_operator")
 * @ORM\Entity
 */
class LogicOperator
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\OperatorValue", inversedBy="logicOperators")
     * @ORM\JoinColumn(name="operator_value_id")
     */
    private $operatorValue;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\ComparedValue", mappedBy="logicOperator")
     */
    private $comparedValues;

    /**
     * Set $id
     * Set $comparedValues
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->comparedValues = new ArrayCollection();
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

    /**
     * Get $comparedValues
     *
     * @return Collection|CompareValue[]
     */
    public function getComparedValues(): Collection
    {
        return $this->comparedValues;
    }
}