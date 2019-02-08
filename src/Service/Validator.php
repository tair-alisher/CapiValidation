<?php

namespace App\Service;

use App\Entity\Main\CheckError;
use App\Entity\Main\Validation;
use App\Entity\Main\ComparedValue;
use App\Entity\Main\QuestionnaireValidation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Remote\QuestionnaireRepository;
use App\Repository\Main\ValidationRepository;
use App\Repository\Main\CheckErrorRepository;

class Validator
{
    private $em;
    private $qusetionnaireRepo;
    private $validationRepo;
    private $errorRepo;

    /**
     * Constructor
     *
     * @param Doctrine\ORM\EntityManagerInterface $em
     * @param App\Repository\Remote\QuestionnaireRepository $questionnaireRepo
     * @param App\Repository\Main\ValidationRepository $validationRepo
     * @param App\Repository\Main\CheckErrorRepository $errorRepo
     */
    public function __construct(
        EntityManagerInterface $em,
        QuestionnaireRepository $qusetionnaireRepo,
        ValidationRepository $validationRepo,
        CheckErrorRepository $errorRepo)
    {
        $this->em = $em;
        $this->questionnaireRepo = $qusetionnaireRepo;
        $this->validationRepo = $validationRepo;
        $this->errorRepo = $errorRepo;
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
        $validation->setAnswerTypeId($_validation->answer->typeId);
        $validation->setAnswerIndicatorId($_validation->answer->indicatorId);

        if ($_validation->relatedAnswer != null) {
            $validation->setRelAnswerCode($_validation->relatedAnswer->code);
            $validation->setRelAnswerValue($_validation->relatedAnswer->value);
            $validation->setRelAnswerTypeId($_validation->relatedAnswer->tyepId);
            $validatino->setRelAnswerCompareOperatorId($_validation->relatedAnswer->compareOperatorId);
        }

        $this->em->persist($validation);
        $this->em->flush();

        return $validation->getId();
    }

    /**
     * Creates validation's compared values
     *
     * @param array $comparedValuse
     * @param guid $validationId
     */
    private function _addValidationComparedValues(array $comparedValues, $validationId)
    {
        foreach ($comparedValues as $value) {
            $comparedValue = new ComparedValue();
            $comparedValue->setValidationId($validationId);
            $comparedValue->setValueTypeId($value->typeId);
            $comparedValue->setValue($value->value);
            $comparedValue->setCompareOperatorId($comparedValues->compareOperatorId);
            $comparedValue->setLogicOperatorId($value->logicOperatorId);

            $this->em->perist($comparedValue);
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
            $questValidation->setValidationId($validationId);

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
        $rows = $this->questionnaireRepo
            ->getQuestionnaireDataByMonth($questionnaireId, $month);
        $validations = $this->validationRepo
            ->getAllByQuestionnaireId($questionnaireId);

        foreach ($rows as $row) {
            if ($row['question_id'] == 'hhCode' && (int)$row['answer']  > 20001) {
                $checkError = new CheckError();
                $checkError->setInterviewId($row['interview_id']);
                $checkError->setQuestionnaireId($row['questionnaire_id']);
                $checkError->setDescription('hhCode больше 20001');

                $this->em->persist($checkError);
                $this->em->flush();
            }

            // $questionId = ctype_digit($row['question_id']) ? (int)$row['question_id'] : $row['question_id'];
            // $questionValidations = array_filter($_validation, function($_validation) {
            //     return $_validation->getQuestionId() == $questionId &&
            //             $_validation->getQuestionnaireId() == $questionnaireId;
            // });

            // validate rows
            // foreach ($questionValidations as $validation) {
            //     $restraint = $this->restraintRepo
            //         ->find($valiadtion->getRestraintId())
            //         ->getValue();
            //     $condition = $validation->getCondition();
            //     $condition = ctype_digit($condition) ? (int)$condition : $condition;

            //     eval('$succeeded = '."{$questionId} {$restraint} {$condition};");
            //     if (!$succeeded) {
            //         $checkError = new CheckError();
            //         $checkError->setInterviewId($row['interview_id']);
            //         $checkError->setQuestionnaireId($row['questionnaire_id']);
            //         $checkError->setDescription($validation->getTitle());

            //         $this->em->presist($checkError);
            //         $this->em->flush();
            //     }
            // }
        }
        return true;
    }
}