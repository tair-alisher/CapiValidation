<?php

namespace App\Repository\Main;

use App\Entity\Main\ValidationError;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ValidationErrorRepository
 */
class ValidationErrorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ValidationError::class);
    }

    public function getAllByQuestionnaireId($questionnaireId): array
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.questionnaireId = :questionnaire_id')
            ->setParameter('questionnaire_id', $questionnaireId)
            ->getQuery();

        return $query->execute();
    }

    public function deleteRowsByQuestionnaireId($questionnaireId)
    {
        $deleted = $this->createQueryBuilder('e')
            ->delete()
            ->andWhere('e.questionnaireId = :questionnaire_id')
            ->setParameter('questionnaire_id', $questionnaireId)
            ->getQuery()
            ->execute();

        return $deleted;
    }
}