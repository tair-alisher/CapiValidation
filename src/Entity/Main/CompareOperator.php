<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.compare_operator")
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
     */
    private $operatorValue;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\Validation", mappedBy="relAnswerCompareOperator")
     */
    private $validations;

    /**
     * Set $id
     * Set $validations
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->validations = new ArrayCollection();
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
     * Get $validations
     *
     * @return Collection|Validation[]
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }
}