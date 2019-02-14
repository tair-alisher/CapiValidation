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
     * @return \App\Entity\Main\Validation
     */
    public function getValidation(): ?Validation
    {
        return $this->validation;
    }

    /**
     * Set $validation
     *
     * @param \App\Entity\Main\Validation
     */
    public function setValidation(?Validation $validation)
    {
        $this->validation = $validation;
    }
}