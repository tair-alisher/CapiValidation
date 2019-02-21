<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.validation")
 * @ORM\Entity(repositoryClass="App\Repository\Main\ValidationRepository")
 */
class Validation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string", name="answer_code")
     */
    private $answerCode;

    /**
     * @ORM\Column(type="guid", name="answer_type_id")
     */
    private $answerTypeId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\InputValueType", inversedBy="validations")
     * @ORM\JoinColumn(name="answer_type_id")
     */
    private $answerType;

    /**
     * @ORM\Column(type="guid", name="answer_indicator_id")
     */
    private $answerIndicatorId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\AnswerIndicator", inversedBy="validations")
     * @ORM\JoinColumn(name="answer_indicator_id")
     */
    private $answerIndicator;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\ComparedValue", mappedBy="validation")
     */
    private $comparedValues;

    /**
     * @ORM\Column(type="string", name="rel_answer_code")
     */
    private $relAnswerCode;

    /**
     * @ORM\Column(type="string", name="rel_answer_value")
     */
    private $relAnswerValue;

    /**
     * @ORM\Column(type="guid", name="rel_answer_type_id")
     */
    private $relAnswerTypeId;

    /**
     * @ORM\Column(type="boolean", name="in_same_section")
     */
    private $inSameSection;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\ComparedValueType", inversedBy="validations")
     * @ORM\JoinColumn(name="rel_answer_type_id")
     */
    private $relAnswerType;

    /**
     * @ORM\Column(type="guid", name="rel_answer_compare_operator_id")
     */
    private $relAnswerCompareOperatorId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\CompareOperator", inversedBy="validations")
     * @ORM\JoinColumn(name="rel_answer_compare_operator_id")
     */
    private $relAnswerCompareOperator;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\QuestionnaireValidation", mappedBy="validation")
     */
    private $questionnaireValidations;

    /**
     * Set $id
     * Set $comparedValues
     * Set $questionnaireValidations
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->comparedValues = new ArrayCollection();
        $this->questionnaireValidations = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get $title
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set $title
     *
     * @param string
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Get $answerCode
     *
     * @return string
     */
    public function getAnswerCode(): ?string
    {
        return $this->answerCode;
    }

    /**
     * Set $answerCode
     *
     * @param string
     */
    public function setAnswerCode(string $answerCode)
    {
        $this->answerCode = $answerCode;
    }

    /**
     * Get $answerTypeId
     *
     * @return guid
     */
    public function getAnswerTypeId()
    {
        return $this->answerTypeId;
    }

    /**
     * Set $answerTypeId
     *
     * @param guid
     */
    public function setAnswerTypeId($typeId)
    {
        $this->answerTypeId = $typeId;
    }

    /**
     * Get $answerType
     *
     * @return \App\Entity\Main\InputValueType
     */
    public function getAnswerType(): ?InputValueType
    {
        return $this->answerType;
    }

    /**
     * Set $answerType
     *
     * @param \App\Entity\Main\InputValueType
     */
    public function setAnswerType(?InputValueType $type)
    {
        $this->answerType = $type;
    }

    /**
     * Returns answer's type name
     *
     * @return null|string
     */
    public function getAnswerTypeName(): ?string
    {
        return $this->getAnswerType()->getValueType()->getName();
    }

    /**
     * Get $answerIndicatorId
     *
     * @return guid
     */
    public function getAnswerIndicatorId()
    {
        return $this->answerIndicatorId;
    }

    /**
     * Set $answerIndicatorId
     *
     * @param guid
     */
    public function setAnswerIndicatorId($indicatorId)
    {
        $this->answerIndicatorId = $indicatorId;
    }

    /**
     * Get $answerIndicator
     *
     * @return \App\Entity\Main\AnswerIndicator
     */
    public function getAnswerIndicator(): ?AnswerIndicator
    {
        return $this->answerIndicator;
    }

    /**
     * Set $answerIndicator
     *
     * @return \App\Entity\Main\AnswerIndicator
     */
    public function setAnswerIndicator(?AnswerIndicator $indicator)
    {
        $this->answerIndicator = $indicator;
    }

    /**
     * Returns name of answer indicator
     *
     * @return null|string
     */
    public function getAnswerIndicatorName(): ?string
    {
        return $this->getAnswerIndicator()->getName();
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

    /**
     * Returns related answer code
     *
     * @return null|string
     */
    public function getRelAnswerCode(): ?string
    {
        return $this->relAnswerCode;
    }

    /**
     * Updates related answer code
     *
     * @param string
     */
    public function setRelAnswerCode(string $relAnswerCode)
    {
        $this->relAnswerCode = $relAnswerCode;
    }

    /**
     * Get $relAnswerTypeId
     *
     * @return guid
     */
    public function getRelAnswerTypeId()
    {
        return $this->relAnswerTypeId;
    }

    /**
     * Set $relAnswerTypeId
     *
     * @param guid
     */
    public function setRelAnswerTypeId($typeId)
    {
        $this->relAnswerTypeId = $typeId;
    }

    /**
     * Returns relAnswerType name
     * @return null|string
     */
    public function getRelAnswerTypeName(): ?string
    {
        return $this->getRelAnswerType()->getValueType()->getName();
    }

    /**
     * Get $relAnswerType
     *
     * @return \App\Entity\Main\ComparedValueType
     */
    public function getRelAnswerType(): ?ComparedValueType
    {
        return $this->relAnswerType;
    }

    /**
     * Set $relAnswerType
     *
     * @param \App\Entity\Main\ComparedValueType
     */
    public function setRelAnswerType(?ComparedValueType $type)
    {
        $this->relAnswerType = $type;
    }

    /**
     * Get $relAnswerValue
     *
     * @return string
     */
    public function getRelAnswerValue(): ?string
    {
        return $this->relAnswerValue;
    }

    /**
     * Set $relAnswerValue
     *
     * @param string
     */
    public function setRelAnswerValue(string $relAnswerValue)
    {
        $this->relAsnwerValue = $relAnswerValue;
    }

    /**
     * Get $relAnswerCompareOperatorId
     *
     * @return guid
     */
    public function getRelAnswerCompareOperatorId()
    {
        return $this->relAnswerCompareOperatorId;
    }

    /**
     * Set $relAnswerCompareOperatorId
     *
     * @param guid
     */
    public function setRelAnswerCompareOperatorId($operatorId)
    {
        $this->relAnswerCompareOperatorId = $operatorId;
    }

    /**
     * Get $relAnswerCompareOperator
     *
     * @return \App\Entity\Main\CompareOperator
     */
    public function getRelAnswerCompareOperator(): ?CompareOperator
    {
        return $this->relAnswerCompareOperator;
    }

    /**
     * Set $relAnswerCompareOperator
     *
     * @param \App\Entity\Main\CompareOperator
     */
    public function setRelAnswerCompareOperator(?CompareOperator $compareOperator)
    {
        $this->relAnswerCompareOperator = $compareOperator;
    }

    /**
     * Returns relAnswerCompareOperator name
     *
     * @return null|string
     */
    public function getRelAnswerCompareOperatorName(): ?string
    {
        return $this->getRelAnswerCompareOperator()->getOperatorValue()->getName();
    }

    public function getInSameSection(): ?bool
    {
        return $this->inSameSection;
    }

    public function setInSameSection($inSameSection)
    {
        $this->inSameSection = $inSameSection;
    }

    /**
     * Get $questionnaireValidations
     *
     * @return Collection|QuestionnaireValidation[]
     */
    public function getQuestionnaireValidation(): Collection
    {
        return $this->questionnaireValidations;
    }
}