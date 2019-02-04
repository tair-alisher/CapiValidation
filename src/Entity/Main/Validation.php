<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollections;
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
     */
    private $answerType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\AnswerIndicator", inversedBy="validations")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\ComparedValueType", inversedBy="validations")
     */
    private $relAnswerType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\CompareOperator", inversedBy="validations")
     */
    private $relAnswerCompareOperator;

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
     * Get id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
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
    public function getRelAnswerCode(): string
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
}