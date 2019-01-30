<?php

namespace App\Service;

use App\Entity\Main\CheckError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Remote\QuestionnaireRepository;
use App\Repository\Main\ValidationRepository;
use App\Repository\Main\RestraintRepository;
use App\Repository\Main\CheckErrorRepository;

class Validator
{
    private $em;
    private $qusetionnaireRepo;
    private $validationRepo;
    private $restraintRepo;
    private $errorRepo;

    /**
     * Constructor
     *
     * @param Doctrine\ORM\EntityManagerInterface $em
     * @param App\Repository\Remote\QuestionnaireRepository $questionnaireRepo
     * @param App\Repository\Main\ValidationRepository $validationRepo
     * @param App\Repository\Main\RestraintRepositroy $restraintRepo
     * @param App\Repository\Main\CheckErrorRepository $errorRepo
     */
    public function __construct(
        EntityManagerInterface $em,
        QuestionnaireRepository $qusetionnaireRepo,
        ValidationRepository $validationRepo,
        RestraintRepository $restraintRepo,
        CheckErrorRepository $errorRepo)
    {
        $this->em = $em;
        $this->questionnaireRepo = $qusetionnaireRepo;
        $this->validationRepo = $validationRepo;
        $this->restraintRepo = $restraintRepo;
        $this->errorRepo = $errorRepo;
    }

    /**
     * Create validation
     *
     * @param App\Entity\Main\Validation $validation
     *
     * @return bool
     */
    public function createValidation($validation)
    {
        $validation->setQuestionnaireTitle(
            $this->qusetionnaireRepo->find($validation->getQuestionnaireId())->getTitle()
        );

        try {
            $this->em->persist($validation);
            $this->em->flush();

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
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