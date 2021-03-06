<?php

namespace App\Entity\Remote;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collection\ArrayCollection;
use Doctrine\Common\Collection\Collection;

/**
 * @ORM\Table(name="readside.interviews")
 * @ORM\Entity(repositoryClass="App\Repository\Remote\InterviewRepository")
 */
class Interview
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", name="interview_id")
     */
    private $interviewId;

    /**
     * Field for mapping query results
     *
     * type is string
     */
    private $questionnaireId;

    /**
     * Field for mapping query results
     *
     * type is string array
     */
    private $questions;

    /**
     * Field for mapping query results
     *
     * type is string array
     */
    private $answers;

    /**
     * Field for mapping query results
     *
     * type array of QuestionData objects
     */
    private $questionsData;

    /**
     * Interview constructor.
     */
    public function __construct()
    {
        $this->questionsData = array();
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

    /**
     * Set $interviewId
     *
     * @param string
     */
    public function setInterviewId($interviewId)
    {
        $this->interviewId = $interviewId;
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
     * Get $questions
     *
     * @return array
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * Set $questions
     *
     * @param array
     */
    public function setQuestions(array $questions)
    {
        $this->questions = $questions;
    }

    /**
     * Get $answers
     *
     * @return array
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    /**
     * Set $answers
     *
     * @param array
     */
    public function setAnswers(array $answers)
    {
        $this->answers = $answers;
    }

    /**
     * Get $questionData
     *
     * @return array
     */
    public function getQuestionsData()
    {
        return $this->questionsData;
    }

    public function pushToQuestionsData(object $questionData)
    {
        array_push($this->questionsData, $questionData);
    }

    /**
     * Set $questionsData
     *
     * @param array
     */
    public function setQuestionsData(array $questionsData)
    {
        $this->questionsData = $questionsData;
    }
}