<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collection\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.compared_value")
 * @ORM\Entity
 */
class ComparedValue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="guid", name="validation_id")
     */
    private $validationId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Validation", inversedBy="comparedValues")
     * @ORM\JoinColumn(name="valdation_id")
     */
    private $validation;

    /**
     * @ORM\Column(type="guid", name="c_value_type_id")
     */
    private $valueTypeId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\ComparedValueType", inversedBy="comparedValues")
     * @ORM\JoinColumn(name="c_value_type_id")
     */
    private $valueType;

    /**
     * @ORM\Column(type="string", name="c_value")
     */
    private $value;

    /**
     * @ORM\Column(type="guid", name="c_operator_id")
     */
    private $compareOperatorId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\CompareOperator", inversedBy="comparedValues")
     * @ORM\JoinColumn(name="c_operator_id")
     */
    private $compareOperator;

    /**
     * @ORM\Column(type="guid", name="logic_operator_id")
     */
    private $logicOperatorId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\LogicOperator", inversedBy="comparedValues")
     * @ORM\JoinColumn(name="logic_operator_id")
     */
    private $logicOperator;

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
     * Get $validationId
     *
     * @return guid
     */
    public function getValidationId()
    {
        return $this->validationId;
    }

    /**
     * Get $validation
     *
     * @return App\Entity\Main\Validation
     */
    public function getValidation(): ?Validation
    {
        return $this->validation;
    }

    /**
     * Set $validation
     *
     * @param App\Entity\Main\Validation
     */
    public function setValidation(?Validation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get $valueTypeId
     *
     * @return guid
     */
    public function getValueTypeId()
    {
        return $this->valueTypeId;
    }

    /**
     * Set $valueTypeId
     *
     * @param guid
     */
    public function setValueTypeId($valueTypeId)
    {
        $this->valueTypeId = $valueTypeId;
    }

    /**
     * Get $valueType
     *
     * @return App\Entity\Main\ComparedValueType
     */
    public function getValueType(): ?ComparedValueType
    {
        return $this->valueType;
    }

    /**
     * Set $valueType
     *
     * @param App\Entity\Main\ComparedValueType
     */
    public function setValueType(?ComparedValueType $valueType)
    {
        $this->valueType = $valueType;
    }

    /**
     * Get $compareOperatorId
     *
     * @return guid
     */
    public function getCompareOperatorId()
    {
        return $this->compareOperatorId;
    }

    /**
     * Set $compareOperatorId
     *
     * @param guid
     */
    public function setCompareOperatorId($operatorId)
    {
        $this->compareOperatorId = $operatorId;
    }

    /**
     * Get $compareOperator
     *
     * @return App\Entity\Main\CompareOperator
     */
    public function getCompareOperator(): ?CompareOperator
    {
        return $this->compareOperator;
    }

    /**
     * Set $compareOperator
     *
     * @param App\Entity\Main\CompareOperator
     */
    public function setCompareOperator(?CompareOperator $operator)
    {
        $this->compareOperator = $operator;
    }

    /**
     * Get $logicOperatorId
     *
     * @return guid
     */
    public function getLogicOperatorId()
    {
        return $this->logicOperatorId;
    }

    /**
     * Set $logicOperatorId
     *
     * @param guid
     */
    public function setLogicOperatorId($operatorId)
    {
        $this->logicOperatorId = $operatorId;
    }

    /**
     * Get $logicOperator
     *
     * @return App\Entity\Main\LogicOperator
     */
    public function getLogicOperator(): ?LogicOperator
    {
        return $this->logicOperator;
    }

    /**
     * Set $logicOperator
     *
     * @param App\Entity\Main\LogicOperator
     */
    public function setLogicOperator(?LogicOperator $operator)
    {
        $this->logicOperator = $operator;
    }
}