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
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Validation", inversedBy="questionnaireValidations")
     * @ORM\JoinColumn(name="validation_id")
     */
    private $validation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Remote\Questionnaire", inversedBy="questionnaireValidations")
     */
    private $questionnaire;

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
}