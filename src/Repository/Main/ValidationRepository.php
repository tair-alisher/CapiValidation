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

    public function getAllByQuestionnaireId($questionnaireId): array
    {
        $query = $this->createQueryBuilder('v')
            ->andWhere('v.questionnaireId = :questionnaire_id')
            ->setParameter('questionnaire_id', $questionnaireId)
            ->getQuery();

        return $query->execute();
    }
}