<?php

namespace App\Entity\Remote;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collection\ArrayCollection;
use Doctrine\Common\Collection\Collection;

/**
 * @ORM\Table(name="readside.interviews")
 * @ORM\Entity(repositoryClass="App\Repsitory\Remote\InterviewRepository")
 */
class Interview
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", name="interview_id")
     */
    private $interviewId;

    /**
     * @ORM\Column(type="string", name="asstring")
     */
    private $answerIsString;

    /**
     * @ORM\Column(type="integer", name="asint")
     */
    private $answerIsInt;

    /**
     * @ORM\Column(type="bigint", name="aslong")
     */
    private $answerIsLong;

    /**
     * @ORM\Column(type="float", name="asdouble")
     */
    private $answerIsDouble;

    /**
     * @ORM\Column(type="datetime", name="asdatetime")
     */
    private $answerIsDateTime;

    /**
     * @ORM\Column(type="json_array", name="aslist")
     */
    private $answerIsList;

    /**
     * @ORM\Column(type="boolean", name="asbool")
     */
    private $answerIsBool;

    /**
     * @ORM\Column(type="array", name="asintarray")
     */
    private $answerIsIntArray;

    /**
     * @ORM\Column(type="json_array", name="asintmatrix")
     */
    private $answerIsIntMatrix;

    /**
     * @ORM\Column(type="json_array", name="asgps")
     */
    private $answerIsGps;

    /**
     * @ORM\Column(type="json_array", name="asyesno")
     */
    private $answerIsYesNo;

    /**
     * @ORM\Column(type="json_array", name="asaudio")
     */
    private $answerIsAudio;

    /**
     * @ORM\Column(type="json_array", name="asarea")
     */
    private $answerIsArea;

    /**
     * Field for mapping query results
     *
     * type is string
     */
    private $questionnaireId;

    /**
     * Field for mapping query results
     *
     * type is string
     */
    private $question;

    /**
     * Field for mapping query results
     *
     * type is string array
     */
    private $questions;

    /**
     * Field for mapping query results
     *
     * type is string
     */
    private $answer;

    /**
     * Field for mapping query results
     *
     * type is string array
     */
    private $answers;

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
     * Get $answerIsString
     *
     * @return string
     */
    public function getAnswerIsString()
    {
        return $this->answerIsString;
    }

    /**
     * Get $answerIsInt
     *
     * @return integer
     */
    public function getAnswerIsInt()
    {
        return $this->answerIsInt;
    }

    /**
     * Get $answerIsLong
     *
     * @return bigint
     */
    public function getAnswerIsLong()
    {
        return $this->answerIsLong;
    }

    /**
     * Get $answerIsDouble
     *
     * @return float
     */
    public function getAnswerIsDouble()
    {
        return $this->answerIsDouble;
    }

    /**
     * Get $answerIsDateTime
     *
     * @return datetime
     */
    public function getAnswerIsDateTime()
    {
        return $this->answerIsDateTime;
    }

    /**
     * Get $answerIsList
     *
     * @return json_array
     */
    public function getAnswerIsList()
    {
        return $this->answerIsList;
    }

    /**
     * Get $answerIsBool
     *
     * @return boolean
     */
    public function getAnswerIsBool()
    {
        return $this->answerIsBool;
    }

    /**
     * Get $answerIsIntArray
     *
     * @return array
     */
    public function getAnswerIsIntArray()
    {
        return $this->answerIsIntArray();
    }

    /**
     * Get $answerIsIntMatrix
     *
     * @return json_array
     */
    public function getAnswerIsIntMatrix()
    {
        return $this->getAnswerIsIntMatrix;
    }

    /**
     * Get $answerIsGps
     *
     * @return json_array
     */
    public function getAnswerIsGps()
    {
        return $this->answerIsGps;
    }

    /**
     * Get $answerIsYesNo
     *
     * @return json_array
     */
    public function getAnswerIsYesNo()
    {
        return $this->answerIsYesNo;
    }

    /**
     * Get $answerIsAudio
     *
     * @return json_array
     */
    public function getAnswerIsAudio()
    {
        return $this->answerIsAudio;
    }

    /**
     * Get $answerIsArea
     *
     * @return json_array
     */
    public function getAnswerIsArea()
    {
        return $this->answerIsArea;
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
     * Get $question
     *
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * Set $question
     *
     * @param string
     */
    public function setQuestion(string $question)
    {
        $this->question = $question;
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
     * Get $answer
     *
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * Set $answer
     *
     * @param string
     */
    public function setAnswer(string $answer)
    {
        $this->answer = $answer;
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
}