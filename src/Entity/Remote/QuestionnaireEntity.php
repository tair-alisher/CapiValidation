<?php

namespace App\Entity\Remote;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="readside.questionnaire_entities")
 */
class QuestionnaireEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;

    /** @ORM\Column(type="string", name="stata_export_caption") */
    private $stataExportCaption;

    /** @ORM\Column(type="string", name="question_text") */
    private $questionText;

    /**
     * Get $id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get $stataExportCaption;
     *
     * @return string
     */
    public function getStataExportCaption()
    {
        return $this->stataExportCaption;
    }

    /**
     * Get $questionText
     *
     * @return string
     */
    public function getQuestionText()
    {
        return $this->questionText;
    }
}