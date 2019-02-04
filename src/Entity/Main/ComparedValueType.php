<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.compared_value_type")
 */
class ComparedValueType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\ValueType", inversedBy="comparedValueTypes")
     */
    private $valueType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\Validation", mappedBy="relAnswerType")
     */
    private $validations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\ComparedValue", mappedBy="valueType")
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
     * Set $valueType
     *
     * @param App\Entity\Main\ValueType
     */
    public function setValueType(?ValueType $valueType)
    {
        $this->valueType = $valueType;
    }

    /**
     * Get $valueType
     *
     * @return App\Entity\Main\ValueType
     */
    public function getValueType(): ?ValueType
    {
        $this->valueType;
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
     * Get $comparedValues
     *
     * @return Collection|ComparedValue[]
     */
    public function getComparedValues(): Collection
    {
        return $this->comparedValues;
    }
}