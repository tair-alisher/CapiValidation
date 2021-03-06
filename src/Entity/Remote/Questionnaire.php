<?php

namespace App\Entity\Remote;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="plainstore.questionnairebrowseitems")
 * @ORM\Entity(repositoryClass="App\Repository\Remote\QuestionnaireRepository")
 */
class Questionnaire
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="guid", name="questionnaireid")
     */
    private $questionnaireId;

    /**
     * @ORM\Column(type="datetime", name="creationdate")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="boolean", name="isdeleted")
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disabled;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return guid
     */
    public function getQuestionnaireId()
    {
        return $this->questionnaireId;
    }

    /**
     * @return datetime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @return boolean
     */
    public function getDisabled()
    {
        return $this->disabled;
    }
}