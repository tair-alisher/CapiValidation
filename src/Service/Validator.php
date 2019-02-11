<?php

namespace App\Service;

use App\Entity\Main\ValidationError;
use App\Entity\Main\Validation;
use App\Entity\Main\ComparedValue;
use App\Entity\Main\QuestionnaireValidation;
use App\Service\Getter;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Remote\InterviewRepository;
use App\Repository\Main\ValidationRepository;
use App\Repository\Main\ValidationErrorRepository;

class Validator
{
    private $em;
    private $interivewRepo;
    private $validationRepo;
    private $errorRepo;
    private $getter;

    /**
     * Constructor
     *
     * @param Doctrine\ORM\EntityManagerInterface $em
     * @param App\Repository\Remote\InterviewRepository $interviewRepo
     * @param App\Repository\Main\ValidationRepository $validationRepo
     * @param App\Repository\Main\ValidationErrorRepository $errorRepo
     * @param App\Service\Getter;
     */
    public function __construct(
        EntityManagerInterface $em,
        InterviewRepository $interivewRepo,
        ValidationRepository $validationRepo,
        ValidationErrorRepository $errorRepo,
        Getter $getter)
    {
        $this->em = $em;
        $this->questionnaireRepo = $interivewRepo;
        $this->validationRepo = $validationRepo;
        $this->errorRepo = $errorRepo;
        $this->getter = $getter;
    }

    /**
     * Create validation
     *
     * @param App\Entity\Main\Validation $validation
     *
     * @return bool
     */
    public function createValidation($_validation)
    {
        $validationId = $this->_createValidation($_validation);
        $this->_addValidationComparedValues($_validation->comparedValues, $validationId);
        $this->_createQuestionnaireValidation($_validation->questionnaires, $validationId);
    }

    /**
     * Creates validation and returns created validation id
     *
     * @param object $_validation
     *
     * @return guid $validationId
     */
    private function _createValidation($_validation)
    {
        $validation = new Validation();
        $validation->setTitle($_validation->title);
        $validation->setAnswerCode($_validation->answer->code);
        $validation->setAnswerType($this->getter->inputValueTypeRepo()->find($_validation->answer->typeId));
        $validation->setAnswerIndicator($this->getter->answerIndicatorRepo()->find($_validation->answer->indicatorId));

        if ($_validation->relatedAnswer != null) {
            $validation->setRelAnswerCode($_validation->relatedAnswer->code);
            $validation->setRelAnswerValue($_validation->relatedAnswer->value);
            $validation->setRelAnswerType($this->getter->comparedValueTypeRepo()->find($_validation->relatedAnswer->typeId));
            $validation->setRelAnswerCompareOperator($this->getter->compareOperatorRepo()->find($_validation->relatedAnswer->compareOperatorId));
        }

        $this->em->persist($validation);
        $this->em->flush();

        return $validation->getId();
    }

    /**
     * Creates validation's compared values
     *
     * @param object $comparedValuse
     * @param guid $validationId
     */
    private function _addValidationComparedValues(object $comparedValues, $validationId)
    {
        foreach ($comparedValues->values as $value) {
            $comparedValue = new ComparedValue();
            $comparedValue->setValidation($this->getter->validationRepo()->find($validationId));
            $comparedValue->setValueType($this->getter->comparedValueTypeRepo()->find($value->typeId));
            $comparedValue->setValue($value->value);
            $comparedValue->setCompareOperator($this->getter->compareOperatorRepo()->find($comparedValues->compareOperatorId));
            if ($value->logicOperatorId != null) {
                $comparedValue->setLogicOperator($this->getter->logicOperatorRepo()->find($value->logicOperatorId));
            }

            $this->em->persist($comparedValue);
        }

        $this->em->flush();
    }

    /**
     * Creates QuestionnaireValidation rows
     *
     * @param array $questionnaires
     * @param guid $validationId
     */
    private function _createQuestionnaireValidation(array $questionnaires, $validationId)
    {
        foreach ($questionnaires as $questId) {
            $questValidation = new QuestionnaireValidation();
            $questValidation->setQuestionnaireId($questId);
            $questValidation->setValidation($this->getter->validationRepo()->find($validationId));

            $this->em->persist($questValidation);
        }

        $this->em->flush();
    }

    /**
     * Validate
     *
     * @param string $questionnaireId
     * @param int $month
     *
     * @return bool
     */
    public function validate($questionnaireId, $month)
    {
        $this->errorRepo->deleteRowsByQuestionnaireId($questionnaireId);
        $interviews = $this->interviewRepo
            ->getInterviewsByQuestoinnaireIdAndMonth($questionnaireId, $month);
        $validations = $this->validationRepo
            ->getAllByQuestionnaireId($questionnaireId);

        foreach ($interviews as $interview) {
            $questionsAndAnswers = $interview->getQuestionsAndAnswers();
            foreach (array_keys($questionAndAnswers) as $question) {
                $answer = $questionAndAnswers[$question];
                $questionValidations = array_filter($validations, function ($_validation) {
                    return $_validation->getAnswerCode() == $question;
                });

                foreach ($questionValidations as $validation) {
                    $answerType = $validation->getAnswerType()->getValueType()->getName();
                    $expression = '$succeeded = ' . "({answerType}){$answer}";

                    $comparedValues = $vaildation->getComparedValues();
                    $firstComparedValue = array_filter($comparedValues, function ($_cv) {
                        return $_cv->getLogicOperator() == null;
                    })[0];
                    $nextComparedValues = array_diff($comparedValues, $firstComparedValue);

                    $compareOperator = $firstComparedValue->getOperatorValue()->getName();
                    $comparedValueType = $firstComparedValue->getValueType()->getName();
                    $comparedValue = $firstComparedValue->getValue();

                    $expression .= " ";

                    eval($expression);

                    if (!$succeeded) {
                        $validationError = new ValidationError();
                        $validationError->setInterviewId($interview->getInterviewId());
                        $validationError->setQuestionnaireId($interview->getQuestionnaireId());
                        $validationError->setDescription($validation->getTitle());

                        $this->em->persist($validationError);
                    }
                }
            }
            $this->em->flush();
        }

        return true;
    }

    // foreach ($rows as $row) {
        //     if ($row['question_id'] == 'hhCode' && (int)$row['answer']  > 20001) {
        //         $checkError = new CheckError();
        //         $checkError->setInterviewId($row['interview_id']);
        //         $checkError->setQuestionnaireId($row['questionnaire_id']);
        //         $checkError->setDescription('hhCode больше 20001');

        //         $this->em->persist($checkError);
        //         $this->em->flush();
        //     }

        //     // $questionId = ctype_digit($row['question_id']) ? (int)$row['question_id'] : $row['question_id'];
        //     // $questionValidations = array_filter($_validation, function($_validation) {
        //     //     return $_validation->getQuestionId() == $questionId &&
        //     //             $_validation->getQuestionnaireId() == $questionnaireId;
        //     // });

        //     // validate rows | $interviews
        //     // foreach ($questionValidations as $validation) {
        //     //     $restraint = $this->restraintRepo
        //     //         ->find($valiadtion->getRestraintId())
        //     //         ->getValue();
        //     //     $condition = $validation->getCondition();
        //     //     $condition = ctype_digit($condition) ? (int)$condition : $condition;

        //     //     eval('$succeeded = '."{$questionId} {$restraint} {$condition};");
        //     //     if (!$succeeded) {
        //     //         $checkError = new CheckError();
        //     //         $checkError->setInterviewId($row['interview_id']);
        //     //         $checkError->setQuestionnaireId($row['questionnaire_id']);
        //     //         $checkError->setDescription($validation->getTitle());

        //     //         $this->em->persist($checkError);
        //     //         $this->em->flush();
        //     //     }
        //     // }
        // }
}