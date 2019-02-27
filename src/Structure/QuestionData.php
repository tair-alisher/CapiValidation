<?php
/**
 * Created by PhpStorm.
 * User: atairakhunov
 * Date: 020, 20 фев 2019
 * Time: 10:25
 */

namespace App\Structure;


class QuestionData
{
    private $sectionId;
    private $questionCode;
    private $answer;

    public function __construct(string $sectionId, string $questionCode, string $answer)
    {
        $this->sectionId = $sectionId;
        $this->questionCode = $questionCode;
        $this->answer = $answer;
    }

    public function getSectionId(): ?string
    {
        return $this->sectionId;
    }

    public function getQuestionCode(): ?string
    {
        return $this->questionCode;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }
}