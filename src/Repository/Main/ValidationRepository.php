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
        $entityManager = $this->getEntityManager();
        $validationIds = $entityManager->createQuery(
            'select qv.validationId
            from App\Entity\Main\QuestionnaireValidation qv
            where qv.questionnaireId = :questionnaireId
            order by qv.id asc'
        )
            ->setParameter('questionnaireId', $questionnaireId)
            ->execute();
        var_dump(array_values($validationIds));

        $query = $this->createQueryBuilder('v')
            ->where('v.id IN(:validation_ids)')
            ->setParameter(':validation_ids', array_values($validationIds))
            ->getQuery();

        return $query->execute();
    }
}