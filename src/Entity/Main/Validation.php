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
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\InputValueType", inversedBy="validations")
     * @ORM\JoinColumn(name="answer_type_id")
     */
    private $answerType;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\ComparedValueType", inversedBy="validations")
     * @ORM\JoinColumn(name="rel_answer_type_id")
     */
    private $relAnswerType;

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
     * Get $answerType
     *
     * @return App\Entity\Main\InputValueType
     */
    public function getAnswerType(): ?InputValueType
    {
        return $this->answerType;
    }

    /**
     * Set $answerType
     *
     * @param App\Entity\Main\InputValueType
     */
    public function setAnswerType(?InputValueType $type)
    {
        $this->answerType = $type;
    }

    /**
     * Get $answerIndicator
     *
     * @return App\Entity\Main\AnswerIndicator
     */
    public function getAnswerIndicator(): ?AnswerIndicator
    {
        return $this->answerIndicator;
    }

    /**
     * Set $answerIndicator
     *
     * @return App\Entity\Main\AnswerIndicator
     */
    public function setAnswerIndicator(?AnswerIndicator $indicator)
    {
        $this->answerIndicator = $indicator;
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
     * Get $relAnswerCode
     *
     * @return string
     */
    public function getRelAnswerCode(): ?string
    {
        return $this->relAnswerCode;
    }

    /**
     * Set $relAnswerCode
     *
     * @param string
     */
    public function setRelAnswerCode(string $relAnswerCode)
    {
        $this->relAnswerCode = $relAnswerCode;
    }

    /**
     * Get $relAnswerType
     *
     * @return App\Entity\Main\ComparedValueType
     */
    public function getRelAnswerType(): ?ComparedValueType
    {
        return $this->relAnswerType;
    }

    /**
     * Set $relAnswerType
     *
     * @param App\Entity\Main\ComparedValueType
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
     * Get $relAnswerCompareOperator
     *
     * @return App\Entity\Main\CompareOperator
     */
    public function getRelAnswerCompareOperator(): ?CompareOperator
    {
        return $this->relAnswerCompareOperator;
    }

    /**
     * Set $relAnswerCompareOperator
     *
     * @param App\Entity\Main\CompareOperator
     */
    public function setRelAnswerCompareOperator(?CompareOperator $compareOperator)
    {
        $this->relAnswerCompareOperator = $compareOperator;
    }

    /**
     * Get $questionnaireValidations
     *
     * @return Collection|QuestionnaireValidation[]
     */
    public function getQuestionnaireValidation()
    {
        return $this->questionnaireValidations;
    }
}