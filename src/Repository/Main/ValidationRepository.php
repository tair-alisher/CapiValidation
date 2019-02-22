<?php

namespace App\Repository\Main;

use App\Entity\Main\Validation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ValidationRepository
 */
class ValidationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Validation::class);
    }

    public function getAllByPages($currentPage = 1, $limit = 10): object
    {
        $query = $this->createQueryBuilder('v')
            ->orderBy('v.title', 'ASC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);

        return $paginator;
    }

    public function paginate($query, $page = 1, $limit)
    {
        $paginator = new Paginator($query);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return $paginator;
    }

    public function getValidationsByQuestionnaireId($questionnaireId): array
    {
        $validations = $this->getEntityManager()->createQueryBuilder()
            ->select('validation')
            ->from('App\Entity\Main\Validation', 'validation')
            ->innerJoin('validation.questionnaireValidations', 'qv')
            ->where('qv.questionnaireId = :questionnaireId')
            ->setParameter('questionnaireId', $questionnaireId)
            ->getQuery()
            ->getResult();

        return $validations;
    }

    public function getQuestionValidationsByQuestionnaireId($questionCode, $questionnaireId): array
    {
        $validations = $this->getEntityManager()->createQueryBuilder()
            ->select('validation')
            ->from('App\Entity\Main\Validation', 'validation')
            ->innerJoin('validation.questionnaireValidations', 'qv')
            ->where('qv.questionnaireId = :questionnaireId')
            ->andWhere('validation.answerCode = :code')
            ->setParameters(['questionnaireId' => $questionnaireId, 'code' => $questionCode])
            ->getQuery()
            ->getResult();

        return $validations;
    }

    /**
     * Removes comparedValue rows by validationId
     *
     * @param $id
     * @throws \Doctrine\DBAL\DBALException
     */
    public function removeComparedValuesByValidationId($id)
    {
        $em = $this->getEntityManager();
        $query = 'delete from public.compared_value where validation_id = :validation_id;';
        $statement = $em->getConnection()->prepare($query);
        $statement->bindValue('validation_id', $id);
        $statement->execute();
    }

    /**
     * Removes validation from questionnaire's validation list
     *
     * @param $id
     * @throws \Doctrine\DBAL\DBALException
     */
    public function removeValidationFromQuestionnaireValidationList($id)
    {
        $em = $this->getEntityManager();
        $query = 'delete from public.questionnaire_validation where validation_id = :validation_id;';
        $statement = $em->getConnection()->prepare($query);
        $statement->bindValue('validation_id', $id);
        $statement->execute();
    }
}