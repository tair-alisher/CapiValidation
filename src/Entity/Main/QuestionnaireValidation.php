<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.questionnaire_validation")
 * @ORM\Entity
 */
class QuestionnaireValidation
{
    /**
     * @ORM\ID
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="questionnaire_id")
     */
    private $questionnaireId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Remote\Questionnaire", inversedBy="questionnaireValidations")
     * @ORM\JoinColumn(name="questionnaire_id")
     */
    private $questionnaire;

    /**
     * @ORM\Column(type="guid", name="validation_id")
     */
    private $validationId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Validation", inversedBy="questionnaireValidations")
     * @ORM\JoinColumn(name="validation_id")
     */
    private $validation;

    /**
     * Set $id
     */
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
     * Get $questionnaireId
     *
     * @return string
     */
    public function getQuestionnaireId()
    {
        return $this->questionnaireId;
    }

    /**
     * Set $questionnaireId
     *
     * @param string
     */
    public function setQuestionnaireId($questionnaireId)
    {
        $this->questionnaireId = $questionnaireId;
    }

    /**
     * Get $questionnaire
     *
     * @return App\Entity\Remote\Questionnaire
     */
    public function getQuestionnaire(): ?\App\Entity\Remote\Questionnaire
    {
        return $this->questionnaire;
    }

    /**
     * Set $questionnaire
     *
     * @param App\Entity\Remote\Questionnaire
     */
    public function setQuestionnaire(?\App\Entity\Remote\Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
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
     * Set $validationId
     *
     * @param guid
     */
    public function setValidationId($validationId)
    {
        $this->validationId = $validationId;
    }

    /**
     * Get $validation
     *
     * @return App\Entity\Main\Validation
     */
    public function getValidation(): ?\App\Entity\Main\Validation
    {
        return $this->validation;
    }

    /**
     * Set $validation
     *
     * @param App\Entity\Main\Validation
     */
    public function setValidation(?\App\Entity\Main\Validation $validation)
    {
        $this->validation = $validation;
    }
}