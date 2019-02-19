<?php

namespace App\Entity\Remote;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="readside.interviewsummaries")
 */
class InterviewSummary
{
    /** @ORM\Column(type="string", name="interviewid") */
    private $interviewId;

    /** @ORM\Column(type="datetime", name="updatedate") */
    private $updateDate;

    /** @ORM\Column(type="string", name="questionnaireidentity") */
    private $questionnaireIdentity;

    /**
     * Get $interviewId
     *
     * @return string
     */
    public function getInterviewId()
    {
        return $this->interviewId;
    }

    /**
     * Get $updateDate
     *
     * @return datetime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Get $questionnaireIdentity
     *
     * @return string
     */
    public function getQuestionnaireIdentity()
    {
        return $this->questionnaireIdentity;
    }
}