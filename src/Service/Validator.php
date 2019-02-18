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
        $expression = '$succeeded =';
        $this->errorRepo->deleteRowsByQuestionnaireId($questionnaireId);
        $interviews = $this->interviewRepo->getInterviewsByQuestionnaireIdAndMonth($questionnaireId, $month);
        $validations = $this->validationRepo->getAllByQuestionnaireId($questionnaireId);

        foreach ($interviews as $interview) {
            $questionsAndAnswers = $interview->getQuestionsAndAnswers();
            foreach ($questionsAndAnswers as $key => $questionAndAnswer) {
                $question = key($questionAndAnswer);
                $answer = $questionAndAnswer[$question];
                $questionValidations = array_filter($validations, function ($_validation) use ($question) {
                    return $_validation->getAnswerCode() == $question;
                });

                foreach ($questionValidations as $validation) {
                    $relatedAnswerSucceeded = true;

                    if ($validation->getRelAnswerCode() != null) {
                        $relAnswerExpression = $this->buildRelatedAnswerExpression($validation, $questionsAndAnswers);
                        eval('$relatedAnswerSucceeded = ' . $relAnswerExpression);
                    }

                    if ($relatedAnswerSucceeded) {
                        $succeeded = true;
                        $answerType = $validation->getAnswerTypeName();
                        $answerIndicator = $validation->getAnswerIndicator()->getName();

                        $_answer = $this->buildAnswer($answer, $answerType, $answerIndicator);

                        // Все сравниваемые значения
                        $comparedValues = $validation->getComparedValues()->toArray();
                        // Обработка первого сравниваемого значения
                        $appropriateFirstComparedValues = array_filter($comparedValues, function ($_cv) {
                            return $_cv->getLogicOperator() == null;
                        });

                        $firstComparedValue = array_shift($appropriateFirstComparedValues);

                        $comparedValuesExpr = $this->buildFirstComparedValueExpression($firstComparedValue, $_answer, $questionsAndAnswers);

                        // Обработка всех последующих сравниваемых значений
                        $nextComparedValues = array_filter($comparedValues, function ($_cv) {
                            return $_cv->getLogicOperator() != null;
                        });

                        if (count($nextComparedValues) > 0) {
                            $comparedValuesExpr .= $this->buildNextComparedValuesExpression($nextComparedValues, $_answer, $questionsAndAnswers);
                        } else {
                            $comparedValuesExpr .= ")";
                        }

                        $expression .= $comparedValuesExpr . ';';

                    eval($expression);

                    if (!$succeeded) {
                        $validationError = new ValidationError();
                        $validationError->setInterviewId($interview->getInterviewId());
                        $validationError->setQuestionnaireId($interview->getQuestionnaireId());
                        $validationError->setDescription($validation->getTitle());

                        $this->em->persist($validationError);
                    }
                        $expression = '$succeeded =';
                    }

                }
            }
            $this->em->flush();
        }

        return $expression;
    }

    /**
     * @param $value
     * @param $type
     * @param $indicator
     * @return \DateTime|false|int|string
     */
    private function buildAnswer($value, $type, $indicator) {
        if ($indicator == 'length') {
            $answer = strlen($value);
        } else {
//                            eval('$answer = ' . "({$answerType}){$answer};");
            $answer = $type == 'datetime' ? date_create_from_format('d.m.Y', $value) : "({$type}){$value}";
        }

        return $answer;
    }

    private function buildRelatedAnswerExpression(Validation $validation, $questionsAndAnswers): string
    {
        $rAnswerCode = $validation->getRelAnswerCode();
        $rQuestionAndAnswer = array_filter($questionsAndAnswers, function ($_qa) use ($rAnswerCode) {
            return key($_qa) == $rAnswerCode;
        });
        $rAnswerValue = array_shift($rQuestionAndAnswer)[$rAnswerCode];
        $rAnswerCompareOperator = $validation->getRelAnswerCompareOperatorName();
        $rAnswerComparedValue = $validation->getRelAnswerValue();
        $rAnswerType = $validation->getRelAnswerTypeName();

        switch ($rAnswerType) {
            case 'int_set':
                $rAnswerComparedValue = new Set($rAnswerComparedValue, 'integer');
                $result = ' (';
                foreach ($rAnswerComparedValue->values() as $value) {
                    $result .= "({$rAnswerValue} {$rAnswerCompareOperator} {$value} || ";
                }
                $result = rtrim($result, ' ||');
                break;
            case 'str_set':
                $rAnswerComparedValue = new Set($rAnswerComparedValue, 'string');
                $result = ' (';
                foreach ($rAnswerComparedValue->values() as $value) {
                    $result .= "({$rAnswerValue} {$rAnswerCompareOperator} {$value} || ";
                }
                $result = rtrim($result, ' ||');
                break;
            case 'range':
                $rAnswerComparedValue = new Range($rAnswerComparedValue);
                $from = $rAnswerComparedValue->from();
                $to = $rAnswerComparedValue->to();
                $result = " ({$rAnswerValue} >= {$from} && {$rAnswerValue} <= {$to}";
                break;
            case 'null':
                $result = " ({$rAnswerValue} {$rAnswerCompareOperator} null";
                break;
            case 'datetime':
                $rAnswerValue = date_create_from_format('d.m.Y', $rAnswerValue);
                $rAnswerComparedValue = date_create_from_format('d.m.Y', $rAnswerComparedValue);
                $result = " ({$rAnswerValue} {$rAnswerCompareOperator} {$rAnswerComparedValue}";
                break;
            case 'indicator':
                $rQuestionAndAnswer = array_filter($questionsAndAnswers, function ($_qa) use ($rAnswerComparedValue) {
                    return key($_qa) == $rAnswerComparedValue;
                });
                $rAnswerComparedValue = array_shift($rQuestionAndAnswer)[$rAnswerComparedValue];
                $result = "{$rAnswerValue} {$rAnswerCompareOperator} {$rAnswerComparedValue}";
                break;
            default:
                $result = " ({$rAnswerValue} {$rAnswerCompareOperator} {$rAnswerComparedValue}";
        }

        return $result . ')';
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

        switch ($comparedValueType) {
            case 'int_set':
                $comparedValue = new Set($comparedValue, 'integer');
                $result = ' (';
                foreach ($comparedValue->values() as $value) {
                    $result .= "({$answer} {$compareOperator} {$value}) || ";
                }
                $result = rtrim($result, ' ||');
                break;
            case 'str_set':
                $comparedValue = new Set($comparedValue, 'string');
                $result = ' (';
                foreach ($comparedValue->values() as $value) {
                    $result .= "({$answer} {$compareOperator} {$value}) || ";
                }
                $result = rtrim($result, ' ||');
                break;
            case 'range':
                $comparedValue = new Range($comparedValue);
                $from = $comparedValue->from();
                $to = $comparedValue->to();
                $result = " ({$answer} >= {$from} && {$answer} <= {$to}";
                break;
            case 'null':
                $result = " ({$answer} {$compareOperator} null";
                break;
            case 'datetime':
                $comparedValue = date_create_from_format('d.m.Y', $comparedValue);
                $result = " ({$answer} {$compareOperator} {$comparedValue}";
                break;
            case 'indicator':
                $questionAndAnswer = array_filter($questionsAndAnswers, function ($_qa) use ($comparedValue) {
                    return key($_qa) == $comparedValue;
                });
                $comparedValue = array_shift($questionAndAnswer)[$comparedValue];
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
        $valuesWithSumLogicOperator = array_filter($comparedValues, function ($_cv) {
            return $_cv->getLogicOperatorName() == 'sum';
        });

        $result = count($valuesWithSumLogicOperator) > 0  ? '' : ')';

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
                        $result .= "({$answer} {$compareOperator} {$value}) {$logicOperator} ";
                    }
                    $result = rtrim($result, ' ||');
                    $result .= ")";
                    break;
                case 'str_set':
                    $comparedValue = new Set($comparedValue, 'string');
                    $result .= " {$logicOperator} (";
                    foreach ($comparedValue->values() as $value) {
                        $result .= "({$answer} {$compareOperator} {$value}) {$logicOperator} ";
                    }
                    $result = rtrim($result, ' ||');
                    $result .= ")";
                    break;
                case 'range':
                    $comparedValue = new Range($comparedValue);
                    $from = $comparedValue->from();
                    $to = $comparedValue->to();
                    $result .= " {$logicOperator} ({$answer} >= {$from} && {$answer} <= {$to})";
                    break;
                case 'null':
                    $result .= " {$logicOperator} ({$answer}) {$compareOperator} null)";
                    break;
                case 'datetime':
                    $comparedValue = date_create_from_format('d.m.Y', $comparedValue);
                    $result .= " {$logicOperator} ({$answer} {$compareOperator} {$comparedValue})";
                    break;
                case 'indicator':
                    $questionAndAnswer = array_filter($questionsAndAnswers, function ($_qa) use ($comparedValue) {
                        return key($_qa) == $comparedValue;
                    });
                    $comparedValue = array_shift($questionAndAnswer)[$comparedValue];
                    if ($logicOperator == 'sum') {
                        $result .= " + {$comparedValue}";
                    } else {
                        $result .= " {$logicOperator} ({$answer} {$compareOperator} {$comparedValue})";
                    }
                    break;
                default:
                    $result .= " {$logicOperator} ({$answer} {$compareOperator} ({$comparedValueType}){$comparedValue})";
            }
        }

        $result .= count($valuesWithSumLogicOperator) > 0 ? ')' : '';

        return $result;
    }
}