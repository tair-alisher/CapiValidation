<?php

namespace App\Repository\Remote;

use App\Entity\Remote\Questionnaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * QuestionnaireRepository
 */
class QuestionnaireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Questionnaire::class);
    }

    public function getAllQuestionnaires($currentPage = 1, $limit = 10): object
    {
        $query = $this->createQueryBuilder('q')
            ->orderBy('q.title', 'ASC')
            ->getQuery();

        $paginator = $this->paginate($query, $currentPage, $limit);

        return $paginator;
        // $query->execute();
    }

    public function paginate($dql, $page = 1, $limit)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return $paginator;
    }
}