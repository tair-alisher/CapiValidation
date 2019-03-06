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

    private $questionnaireId;
    private $interview;
    private $questionsData;
    private $section;
    private $question;
    private $answer;

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
            $validation->setInSameSection($_validation->relatedAnswer->inSameSection);
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
            $comparedValue->setInSameSection($value->inSameSection);

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
     */
    public function validate($questionnaireId, $offset, bool $deleteCurrentErrors)
    {
        $completed = false;

        if ($deleteCurrentErrors) {
            $this->errorRepo->deleteCurrentQuestionnaireValidationErrors($questionnaireId);
        }

        $this->questionnaireId = $questionnaireId;
        $interviews = $this->interviewRepo->getInterviewsByQuestionnaireId($questionnaireId, $offset, $limit = 1000);
        if (count($interviews) <= 0) {
            $completed = true;
            return $completed;
        }

        $this->checkInterviewsData($interviews);

        return $completed;
    }

    /**
     * Validates all interviews data
     *
     * @param array $interviews
     */
    private function checkInterviewsData(array $interviews)
    {
        foreach ($interviews as $interview) {
            $this->interview = $interview;
            $this->questionsData = $interview->getQuestionsData();
            $this->checkCurrentInterviewData();
            $this->em->flush();
        }
    }

    /**
     * Validates current interview data
     */
    private function checkCurrentInterviewData()
    {
        foreach ($this->questionsData as $questionData) {
            $this->section = $questionData->getSectionId();
            $this->question = $questionData->getQuestionCode();
            $this->answer = $questionData->getAnswer();
            $questionValidations = $this->validationRepo->getQuestionValidationsByQuestionnaireId($this->question, $this->questionnaireId);

            $this->checkQuestionValidations($questionValidations);
        }
    }

    /**
     * Passes through all question validations
     *
     * @param array $questionValidations
     */
    private function checkQuestionValidations(array $questionValidations)
    {
        foreach ($questionValidations as $validation) {
            if ($this->relatedQuestionAnswerIsValid($validation)) {
                $this->checkQuestionAnswer($validation);
            }
        }
    }

    /**
     * Checks if related question answer's value is valid
     *
     * @param Validation $validation
     * @return bool
     */
    private function relatedQuestionAnswerIsValid(Validation $validation)
    {
        $isValid = true;

        if ($validation->getRelAnswerCode() != null) {
            $expression = $this->buildRelatedAnswerExpression($validation);
            eval('$isValid =' . $expression);
        }

        return $isValid;
    }

    /**
     * Builds related answer's logic expression for eval() func
     *
     * @param Validation $validation
     * @param $questionsAndAnswers
     * @return string
     */
    private function buildRelatedAnswerExpression(Validation $validation): string
    {
        $rAnswerCode = $validation->getRelAnswerCode();
        $rAnswerValue = $this->getIndicatorComparedValue($validation->getInSameSection(), $rAnswerCode);

        $rAnswerCompareOperator = $validation->getRelAnswerCompareOperatorName();
        $rAnswerComparedValue = $validation->getRelAnswerValue();
        $rAnswerType = $validation->getRelAnswerTypeName();

        switch ($rAnswerType) {
            case 'int_set':
                $rAnswerComparedValue = new Set($rAnswerComparedValue, 'integer');
                $result = ' (';
                foreach ($rAnswerComparedValue->values() as $value) {
                    $result .= "({$rAnswerValue} {$rAnswerCompareOperator} {$value}) || ";
                }
                $result = rtrim($result, ' ||');
                break;
            case 'str_set':
                $rAnswerComparedValue = new Set($rAnswerComparedValue, 'string');
                $result = ' (';
                foreach ($rAnswerComparedValue->values() as $value) {
                    $result .= "({$rAnswerValue} {$rAnswerCompareOperator} {$value}) || ";
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
                $rAnswerValue = strlen($rAnswerValue) > 0 ? $rAnswerValue : 'null';
                $result = " ({$rAnswerValue} {$rAnswerCompareOperator} null";
                break;
            case 'datetime':
                $rAnswerValue = date_create_from_format('d.m.Y', $rAnswerValue);
                $rAnswerComparedValue = date_create_from_format('d.m.Y', $rAnswerComparedValue);
                $result = " ({$rAnswerValue} {$rAnswerCompareOperator} {$rAnswerComparedValue}";
                break;
            case 'indicator':
                $rAnswerComparedValue = $this->interviewRepo->getQuestionAnswer($this->interview->getInterviewId, $rAnswerComparedValue);
                $result = "{$rAnswerValue} {$rAnswerCompareOperator} {$rAnswerComparedValue}";
                break;
            case 'json':
                $productCode = explode('_', $this->section)[1];
                if (strpos($rAnswerValue, ',') !== false) {
                    $result = "{$productCode} {$rAnswerCompareOperator} {$rAnswerValue}";
                } else {
                    $rAnswerComparedValue = new Set($rAnswerComparedValue, 'integer');
                    $result = ' (';
                    foreach ($rAnswerComparedValue->values() as $value) {
                        $result .= "({$productCode} {$rAnswerCompareOperator} {$value}) || ";
                    }
                    $result = rtrim($result, ' ||');
                }
                break;
            default:
                $result = " (({$rAnswerType}){$rAnswerValue} {$rAnswerCompareOperator} ({$rAnswerType}){$rAnswerComparedValue}";
        }

        return $result . ');';
    }

    /**
     * Checks if answer is valid
     *
     * @param Validation $validation
     */
    private function checkQuestionAnswer(Validation $validation)
    {
        $succeeded = true;
        $answerType = $validation->getAnswerTypeName();
        $answerIndicator = $validation->getAnswerIndicatorName();

        $answer = $this->buildAnswer($answerType, $answerIndicator);
        $comparedValues = $validation->getComparedValues()->toArray();
        $comparedValuesExpression = '$succeeded =';

        $firstComparedValue = $this->getFirstComparedValue($comparedValues);
        $comparedValuesExpression .= $this->buildFirstComparedValueExpression($firstComparedValue, $answer);

        $nextComparedValues = $this->getComparedValuesExceptFirst($comparedValues);
        $comparedValuesExpression .= $this->buildNextComparedValuesExpression($nextComparedValues, $answer) . ';';

        eval($comparedValuesExpression);

        if (!$succeeded) {
            $this->initAndPersistValidationError($validation);
        }
    }

    /**
     * @param $type
     * @param $indicator
     * @return \DateTime|false|int|string
     */
    private function buildAnswer($type, $indicator) {
        if ($indicator == 'length') {
            $answer = strlen($this->answer);
        } else {
            // eval('$answer = ' . "({$answerType}){$answer};");
            $answer = $type == 'datetime' ? date_create_from_format('Y-m-d H:i:s', $this->answer) : "({$type}){$this->answer}";
        }

        return $answer;
    }

    /**
     * Returns first compared values from the list
     *
     * @param array $comparedValues
     * @return ComparedValue|null
     */
    private function getFirstComparedValue(array $comparedValues): ?ComparedValue
    {
        $appropriateValues = array_filter($comparedValues, function ($_cv) {
            return $_cv->getLogicOperator() == null;
        });

        return array_shift($appropriateValues);
    }

    /**
     * Returns compared values except first from the list
     *
     * @param array $comparedValues
     * @return array|null
     */
    private function getComparedValuesExceptFirst(array $comparedValues): ?array
    {
        return array_filter($comparedValues, function ($_cv) {
            return $_cv->getLogicOperator() != null;
        });
    }

    /**
     * Creates and persists ValidationError instance
     *
     * @param Validation $validation
     */
    private function initAndPersistValidationError(Validation $validation)
    {
        $error = new ValidationError();
        $error->setInterviewId($this->interview->getInterviewId());
        $error->setQuestionnaireId($this->interview->getQuestionnaireId());
        $error->setDescription($validation->getTitle());

        $this->em->persist($error);
    }

    /**
     * Builds first compared value's logic expression for eval() func
     *
     * @param ComparedValue $comparedValueObj
     * @param $answer
     * @return string
     */
    private function buildFirstComparedValueExpression(ComparedValue $comparedValueObj, $answer): string
    {
        $compareOperator = $comparedValueObj->getCompareOperatorName();
        $comparedValueType = $comparedValueObj->getValueTypeName();
        $comparedValue = $comparedValueObj->getValue();

        switch ($comparedValueType) {
            case 'int_set':
                $comparedValue = new Set($comparedValue, 'integer');
                $result = $this->buildFirstSetComparedValueExpression($comparedValue, $answer, $compareOperator);
                break;
            case 'str_set':
                $comparedValue = new Set($comparedValue, 'string');
                $result = $this->buildFirstSetComparedValueExpression($comparedValue, $answer, $compareOperator);
                break;
            case 'range':
                $comparedValue = new Range($comparedValue);
                $from = $comparedValue->from();
                $to = $comparedValue->to();
                $result = " ({$answer} >= {$from} && {$answer} <= {$to}";
                break;
            case 'null':
                try {
                    $result = " ({$answer} {$compareOperator} null";
                } catch (\Exception $e) {
                    $answer = $answer->format('Y-m-d');
                    $result = " ('{$answer}' {$compareOperator} null";
                }
                break;
            case 'datetime':
                $comparedValue = date_create_from_format('d.m.Y', $comparedValue);
                $result = " ({$answer} {$compareOperator} {$comparedValue}";
                break;
            case 'indicator':
                $comparedValue = $this->getIndicatorComparedValue($comparedValueObj->getInSameSection(), $comparedValue);
                $result = " ({$answer} {$compareOperator} {$comparedValue}";
                break;
            default:
                $result = " ({$answer} {$compareOperator} ({$comparedValueType}){$comparedValue}";
        }

        return $result;
    }

    /**
     * Builds and returns expression for the first compared value if it's Set
     *
     * @param Set $comparedValue
     * @param $answer
     * @param $compareOperator
     * @return null|string
     */
    private function buildFirstSetComparedValueExpression(Set $comparedValue, $answer, $compareOperator): ?string
    {
        $result = ' (';
        foreach ($comparedValue->values() as $value) {
            $result .= "({$answer} {$compareOperator} {$value}) || ";
        }
        $result = rtrim($result, ' ||');

        return $result;
    }

    /**
     * Returns answer on question by interview id, question code and section if required
     *
     * @param bool $inSameSection
     * @param $comparedValue
     * @return null|string
     */
    private function getIndicatorComparedValue(bool $inSameSection, $comparedValue)
    {
        $value = $comparedValue;
        if ($inSameSection) {
            $value = $this->interviewRepo->getQuestionAnswerInSection(
                $this->interview->getInterviewId(), $value, $this->section
            );
        } else {
            $value = $this->interviewRepo->getQuestionAnswer($this->interview->getInterviewId(), $value);
        }

        return $value;
    }

    /**
     * Builds compared values' (except first) logic expression for eval() func
     *
     * @param array $comparedValues
     * @param $answer
     * @return string
     */
    private function buildNextComparedValuesExpression(array $comparedValues, $answer): string
    {
        if (count($comparedValues) <= 0) { return ')'; }

        $existsComparedValueWithSumLogicOperator = $this->existsComparedValueWithSumLogicOperator($comparedValues);
        $expression = $existsComparedValueWithSumLogicOperator ? '' : ')';
        foreach ($comparedValues as $nextComparedValue) {
            $expression .= $this->buildExpressionForCurrentComparedValue($nextComparedValue, $answer);
        }
        $expression .= $existsComparedValueWithSumLogicOperator ? ')' : '';

        return $expression;
    }

    /**
     * Returns true if in the list of compared values exists compared value with a 'sum' logic operator, otherwise false
     *
     * @param array $comparedValues
     * @return bool
     */
    private function existsComparedValueWithSumLogicOperator(array $comparedValues)
    {
        $valuesWithSumLogicOperator = array_filter($comparedValues, function ($_cv) {
            return $_cv->getLogicOperatorName() == 'sum';
        });

        return count($valuesWithSumLogicOperator) > 0;
    }

    /**
     * Builds for given compared value logic expression for eval() func
     *
     * @param ComparedValue $nextComparedValue
     * @param $answer
     * @return string
     */
    private function buildExpressionForCurrentComparedValue(ComparedValue $nextComparedValue, $answer)
    {
        $compareOperator = $nextComparedValue->getCompareOperatorName();
        $logicOperator = $nextComparedValue->getLogicOperatorName();
        $comparedValueType = $nextComparedValue->getValueTypeName();
        $comparedValue = $nextComparedValue->getValue();

        $expression = '';

        switch ($comparedValueType) {
            case 'int_set':
                $comparedValue = new Set($comparedValue, 'integer');

                $expression .= " {$logicOperator} (";
                foreach ($comparedValue->values() as $value) {
                    $expression .= "({$answer} {$compareOperator} {$value}) {$logicOperator} ";
                }
                $expression = rtrim($expression, ' ||');
                $expression .= ")";
                break;
            case 'str_set':
                $comparedValue = new Set($comparedValue, 'string');
                $expression .= " {$logicOperator} (";
                foreach ($comparedValue->values() as $value) {
                    $expression .= "({$answer} {$compareOperator} {$value}) {$logicOperator} ";
                }
                $expression = rtrim($expression, ' ||');
                $expression .= ")";
                break;
            case 'range':
                $comparedValue = new Range($comparedValue);
                $from = $comparedValue->from();
                $to = $comparedValue->to();
                $expression .= " {$logicOperator} ({$answer} >= {$from} && {$answer} <= {$to})";
                break;
            case 'null':
                try {
                    $expression .= " {$logicOperator} ({$answer}) {$compareOperator} null)";
                } catch (\Exception $e) {
                    $answer = $answer->format('Y-m-d');
                    $expression .= " {$logicOperator} ('{$answer}') {$compareOperator} null)";
                }
                break;
            case 'datetime':
                $comparedValue = date_create_from_format('d.m.Y', $comparedValue);
                $expression .= " {$logicOperator} ({$answer} {$compareOperator} {$comparedValue})";
                break;
            case 'indicator':
                $comparedValue = $this->getIndicatorComparedValue($nextComparedValue->getInSameSection(), $comparedValue);

                if ($logicOperator == 'sum') {
                    $expression .= " + {$comparedValue}";
                } else {
                    $expression .= " {$logicOperator} ({$answer} {$compareOperator} {$comparedValue})";
                }
                break;
            default:
                $expression .= " {$logicOperator} ({$answer} {$compareOperator} ({$comparedValueType}){$comparedValue})";
        }

        return $expression;
    }
}