<?php

namespace App\Entity\Remote;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="readside.interviews_id")
 */
class InterviewId
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;

    /** @ORM\Column(type="string", name="interview_id") */
    private $interviewId;

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
     * Get $interviewId
     *
     * @return string
     */
    public function getInterviewId()
    {
        return $this->interviewId;
    }
}