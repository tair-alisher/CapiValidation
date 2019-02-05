<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collection\ArrayCollection;
use Doctrine\Common\Collection\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.input_value_type")
 * @ORM\Entity
 */
class InputValueType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\ValueType", inversedBy="InputValueTypes")
     * @ORM\JoinColumn(name="value_type_id")
     */
    private $valueType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\Validation", mappedBy="answerType")
     */
    private $validations;

    /**
     * Set $id
     * Set $answers
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
        return $this->valueType;
    }

    /**
     * Get $validations
     *
     * @return Collection|Validation[]
     */
    public function getValidations()
    {
        return $this->validations;
    }
}