<?php

namespace App\Service;

use App\Structure\Set;
use App\Structure\Range;

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
    private $interviewRepo;
    private $validationRepo;
    private $errorRepo;
    private $getter;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManagerInterface
     * @param \App\Repository\Remote\InterviewRepository
     * @param \App\Repository\Main\ValidationRepository
     * @param \App\Repository\Main\ValidationErrorRepository
     * @param \App\Service\Getter;
     */
    public function __construct(
        EntityManagerInterface $em,
        InterviewRepository $interviewRepo,
        ValidationRepository $validationRepo,
        ValidationErrorRepository $errorRepo,
        Getter $getter)
    {
        $this->em = $em;
        $this->interviewRepo = $interviewRepo;
        $this->validationRepo = $validationRepo;
        $this->errorRepo = $errorRepo;
        $this->getter = $getter;
    }

    /**
     * Creates validation, its compared values, binds validation to questionnaires
     *
     * @param \App\Entity\Main\Validation
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
     * @param object
     *
     * @return guid id of created validation
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
     * @param object
     * @param guid
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
     * @param array
     * @param guid
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
     * Validates questionnaire's interview data for a specific month
     *
     * @param string
     * @param int
     *
     * @return string
     */
    public function validate($questionnaireId, $month)
    {
        $this->errorRepo->deleteRowsByQuestionnaireId($questionnaireId);
        $interviews = $this->interviewRepo->getInterviewsByQuestionnaireIdAndMonth($questionnaireId, $month);
        $validations = $this->validationRepo->getAllByQuestionnaireId($questionnaireId);
        var_dump($validations);

        foreach ($interviews as $interview) {
            $questionsAndAnswers = $interview->getQuestionsAndAnswers();
            foreach ($questionsAndAnswers as $key => $questionAndAnswer) {
                $question = key($questionAndAnswer);
                $answer = $questionAndAnswer[$question];
                $questionValidations = array_filter($validations, function ($_validation) use ($question) {
                    return $_validation->getAnswerCode() == $question;
                });

                foreach ($questionValidations as $validation) {
                    $succeeded = true;
                    $answerType = $validation->getAnswerTypeName();
                    $answerIndicator = $validation->getAnswerIndicator()->getName();

                    if ($answerIndicator == 'length') {
                        $answer = strlen($answer);
                    } else {
                        if ($answerType == 'datetime') {
                            $answer = date_create_from_format('d.m.Y', $answer);
                        } else {
                            eval('$answer = ' . "({$answerType}){$answer};");
                        }
                    }

                    // Все сравниваемые значения
                    $comparedValues = $validation->getComparedValues();
                    $expression = '$succeeded = ';

                    // Обработка первого сравниваемого значения
                    $firstComparedValue = array_filter($comparedValues, function ($_cv) {
                        return $_cv->getLogicOperator() == null;
                    })[0];

                    $comparedValuesExpr = $this->buildFirstComparedValueExpression($firstComparedValue, $answer, $questionsAndAnswers);

                    // Обработка всех последующих сравниваемых значений
                    $nextComparedValues = array_diff($comparedValues, $firstComparedValue);

                    if (count($nextComparedValues) > 0) {
                        $comparedValuesExpr .= $this->buildNextComparedValuesExpression($nextComparedValues, $answer, $questionsAndAnswers);
                    } else {
                        $comparedValuesExpr .= ")";
                    }


                    $expression .= $comparedValuesExpr;
                    return $expression;

//                    eval($expression);
//
//                    if (!$succeeded) {
//                        $validationError = new ValidationError();
//                        $validationError->setInterviewId($interview->getInterviewId());
//                        $validationError->setQuestionnaireId($interview->getQuestionnaireId());
//                        $validationError->setDescription($validation->getTitle());
//
//                        $this->em->persist($validationError);
//                    }
                }
            }
//            $this->em->flush();
        }

        return "";
    }

    /**
     * @param ComparedValue $comparedValueObj
     * @param $answer
     * @param $questionsAndAnswers
     * @return string
     */
    private function buildFirstComparedValueExpression(ComparedValue $comparedValueObj, $answer, $questionsAndAnswers): string
    {
        $compareOperator = $comparedValueObj->getCompareOperatorName();
        $comparedValueType = $comparedValueObj->getValueTypeName();
        $comparedValue = $comparedValueObj->getValue();

        $result = '';

        switch ($comparedValueType) {
            case 'int_set':
                $comparedValue = new Set($comparedValue, 'integer');
                $result = "(";
                foreach ($comparedValue->values() as $value) {
                    $result .= " ({$answer} {$compareOperator} {$value}) or";
                }
                // remove last 'or'
                $result = preg_replace('/\W\w+\s*(\W*)$/', '$1', $result);
                break;
            case 'str_set':
                $comparedValue = new Set($comparedValue, 'string');
                $result = "(";
                foreach ($comparedValue->values() as $value) {
                    $result .= " ({$answer} {$compareOperator} {$value}) or";
                }
                // remove last 'or'
                $result = preg_replace('/\W\w+\s*(\W*)$/', '$1', $result);
                break;
            case 'range':
                $comparedValue = new Range($comparedValue);
                $from = $comparedValue->from();
                $to = $comparedValue->to();
                $result = " ({$answer} >= {$from} && {$answer} <= {$to}";
                break;
            case 'null':
                $result = " ({$answer}) {$compareOperator} null";
                break;
            case 'datetime':
                $comparedValue = date_create_from_format('d.m.Y', $comparedValue);
                $result = " ({$answer} {$compareOperator} {$comparedValue}";
                break;
            case 'indicator':
                $comparedValue = $questionsAndAnswers[$comparedValue];
                $result = " ({$answer} {$compareOperator} {$comparedValue}";
                break;
            default:
                $result = " ({$answer} {$compareOperator} ({$comparedValueType}){$comparedValue}";
        }

        return $result;
    }

    /**
     * @param array $comparedValues
     * @param $answer
     * @param $questionsAndAnswers
     * @return string
     */
    private function buildNextComparedValuesExpression(array $comparedValues, $answer, $questionsAndAnswers): string
    {
        $sumLogicOperatorExists = count(
            array_filter($comparedValues, function ($_cv) {
                return $_cv->getLogicOperator == 'sum';
            })
        ) > 0;

        if (!$sumLogicOperatorExists) {
            $result = ')';
        }

        foreach ($comparedValues as $nextComparedValue) {
            $compareOperator = $nextComparedValue->getCompareOperatorName();
            $logicOperator = $nextComparedValue->getLogicOperatorName();
            $comparedValueType = $nextComparedValue->getValueTypeName();
            $comparedValue = $nextComparedValue->getValue();

            switch ($comparedValueType) {
                case 'int_set':
                    $comparedValue = new Set($comparedValue, 'integer');
                    $result .= " {$logicOperator} (";
                    foreach ($comparedValue->values() as $value) {
                        $result .= " ({$answer} {$compareOperator} {$value}) {$logicOperator}";
                    }
                    // remove last logic operator
                    $result = preg_replace('/\W\w+\s*(\W*)$/', '$1', $result);
                    $result .= ")";
                    break;
                case 'str_set':
                    $comparedValue = new Set($comparedValue, 'string');
                    $result .= " {$logicOperator} (";
                    foreach ($comparedValue->values() as $value) {
                        $result .= " ({$answer} {$compareOperator} {$value}) {$logicOperator}";
                    }
                    // remove last logic operator
                    $result = preg_replace('/\W\w+\s*(\W*)$/', '$1', $result);
                    $result .= ")";
                    break;
                case 'range':
                    $comparedValue = new Range($comparedValue);
                    $from = $comparedValue->from();
                    $to = $comparedValue->to();
                    $result = " {$logicOperator} ({$answer} >= {$from} && {$answer} <= {$to})";
                    break;
                case 'null':
                    $result = " {$logicOperator} ({$answer}) {$compareOperator} null)";
                    break;
                case 'datetime':
                    $comparedValue = date_create_from_format('d.m.Y', $comparedValue);
                    $result = " {$logicOperator} ({$answer} {$compareOperator} {$comparedValue})";
                    break;
                case 'indicator':
                    $comparedValue = $questionsAndAnswers[$comparedValue];
                    if ($logicOperator == 'sum') {
                        $result = " + {$comparedValue}";
                    } else {
                        $result = " {$logicOperator} ({$answer} {$compareOperator} {$comparedValue})";
                    }
                    break;
                default:
                    $result = " {$logicOperator} ({$answer} {$compareOperator} ({$comparedValueType}){$comparedValue})";
            }
        }

        $result .= $sumLogicOperatorExists ? ')' : '';

        return $result;
    }
}