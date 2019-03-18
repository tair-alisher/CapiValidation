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

    public function getAllOrderedByTitle()
    {
        return $this->findBy(array(), array('title' => 'ASC'));
    }

    public function getAllQuestionnaires($currentPage = 1, $limit = 10)
    {
        $query = $this->createQueryBuilder('q')
            ->orderBy('q.title', 'ASC')
            ->getQuery();

        $pageDivider = $this->paginate($query, $currentPage, $limit);

        return $pageDivider;
    }

    public function paginate($dql, $page = 1, $limit)
    {
        $pageDivider = new Paginator($dql);

        $pageDivider->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        return $pageDivider;
    }

    public function getTitleIdArray(): array
    {
        $items = $this->getAllOrderedByTitle();
        $titleIdArray = array();

        foreach ($items as $item) {
            $titleIdArray[$item->getTitle()] = $item->getId();
        }

        return $titleIdArray;
    }

    public function getQuestionnairesByIds($questionnairesId)
    {
        return $this->createQueryBuilder('q')
            ->where('q.id IN (:ids)')
            ->orderBy('q.title')
            ->setParameter('ids', $questionnairesId)
            ->getQuery()
            ->getResult();
    }
}