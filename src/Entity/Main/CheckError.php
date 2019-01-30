<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.check_error")
 * @ORM\Entity(repositoryClass="App\Repository\Main\CheckErrorRepository")
 */
class CheckError
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="interview_id")
     */
    private $interviewId;

    /**
     * @ORM\Column(type="string", name="questionnaire_id")
     */
    private $questionnaireId;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

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
     * Set interviewId
     *
     * @param string $interviewId
     */
    public function setInterviewId($interviewId)
    {
        $this->interviewId = $interviewId;
    }

    /**
     * Get interviewId
     *
     * @return string $interviewId
     */
    public function getInterviewId()
    {
        return $this->interviewId;
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
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }
}