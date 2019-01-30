<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
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
    public $title;

    /**
     * @ORM\Column(type="string", name="question_id")
     */
    private $questionId;

    /**
     * @ORM\Column(type="guid", name="restraint_id")
     */
    private $restraintId;

    /**
     * @ORM\Column(type="string")
     */
    private $condition;

    /**
     * @ORM\Column(type="string", name="related_question_id", nullable=true)
     */
    private $relatedQuestionId = null;

    /**
     * @ORM\Column(type="string", name="related_question_condition", nullable=true)
     */
    private $relatedQuestionCondition = null;

    /**
     * @ORM\Column(type="string", name="questionnaire_id")
     */
    private $questionnaireId;

    /**
     * @ORM\Column(type="string", name="questionnaire_title")
     */
    private $questionnaireTitle;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set questionId
     *
     * @param string $questionId
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;
    }

    /**
     * Get questionId
     *
     * @return string $questionId
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * Set restraintId
     *
     * @param guid $restraintId
     */
    public function setRestraintId($restraintId)
    {
        $this->restraintId = $restraintId;
    }

    /**
     * Get restraintId
     *
     * @return guid $restraintId
     */
    public function getRestraintId()
    {
        return $this->restraintId;
    }

    /**
     * Set condition
     *
     * @param string $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * Get condition
     *
     * @return string $condition
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Set relatedQuestionId
     *
     * @param string $relatedQuestionId
     */
    public function setRelatedQuestionId($relatedQuestionId)
    {
        $this->relatedQuestionId = $relatedQuestionId;
    }

    /**
     * Get relatedQuestionId
     *
     * @return string $relatedQuestionId
     */
    public function getRelatedQuestionId()
    {
        return $this->relatedQuestionId;
    }

    /**
     * Set relatedQuestionCondition
     *
     * @param string $relatedQuestionCondition
     */
    public function setRelatedQuestionCondition($relatedQuestionCondition)
    {
        $this->relatedQuestionCondition = $relatedQuestionCondition;
    }

    /**
     * Get relatedQuestionCondition
     *
     * @return string $relatedQuestionCondition
     */
    public function getRelatedQuestionCondition()
    {
        return $this->relatedQuestionCondition;
    }

    /**
     * Set questionnaireId
     *
     * @param string $questionnaireId
     */
    public function setQuestionnaireId($questionnaireId)
    {
        $this->questionnaireId = $questionnaireId;
    }

    /**
     * Get questionnaireId
     *
     * @return string $questionnaireId
     */
    public function getQuestionnaireId()
    {
        return $this->questionnaireId;
    }

    /**
     * Set questionnaireTitle
     *
     * @param string $questionnaireTitle
     */
    public function setQuestionnaireTitle($questionnaireTitle)
    {
        $this->questionnaireTitle = $questionnaireTitle;
    }

    /**
     * Get questionnaireTitle
     *
     * @return string $questionnaireTitle
     */
    public function getQuestionnaireTitle()
    {
        return $this->questionnaireTitle;
    }
}