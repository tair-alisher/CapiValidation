<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.compare_operator")
 * @ORm\Entity
 */
class CompareOperator
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\OperatorValue", inversedBy="compareOperators")
     * @ORM\JoinColumn(name="operator_value_id")
     */
    private $operatorValue;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\Validation", mappedBy="relAnswerCompareOperator")
     */
    private $validations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\ComparedValue", mappedBy="compareOperator")
     */
    private $comparedValues;

    /**
     * Set $id
     * Set $validations
     * Set $comparedValues
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->validations = new ArrayCollection();
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
     * @param \App\Entity\Main\OperatorValue
     */
    public function setOperatorValue(?OperatorValue $operatorValue)
    {
        $this->operatorValue = $operatorValue;
    }

    /**
     * Get $operatorValue
     *
     * @return \App\Entity\Main\OperatorValue
     */
    public function getOperatorValue() : ?OperatorValue
    {
        return $this->operatorValue;
    }

    /**
     * Get $validations
     *
     * @return Collection|Validation[]
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    /**
     * Get $compareValues
     *
     * @return Collection|CompareValue[]
     */
    public function getComparedValues(): Collection
    {
        return $this->comparedValues;
    }
}