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

    public function getQuestionnaireDataByMonth($questionnaireId, $month): array
    {
        $conn = $this->getEntityManager('server')->getConnection();
        $noAnswer = 'нет ответа';

        $query = '
        select
            summary.summaryid as interview_id,
            summary.questionnaireidentity as questionnaire_id,
            question_entity.question_text as question,
            question_entity.stata_export_caption as question_id,
            coalesce(
                interview.asstring,
                cast(interview.asint as varchar),
                cast(interview.aslong as varchar),
                cast(interview.asdouble as varchar),
                cast(interview.asdatetime as varchar),
                cast(interview.aslist as varchar),
                cast(interview.asbool as varchar),
                cast(interview.asintarray as varchar),
                cast(interview.asintmatrix as varchar),
                cast(interview.asgps as varchar),
                cast(interview.asyesno as varchar),
                cast(interview.asaudio as varchar),
                cast(interview.asarea as varchar),
                :no_answer
            ) as answer,
            summary.updatedate as updatedat
        from
            readside.interviews as interview
        join
            readside.interviews_id as interview_id
        on
            interview.interviewid = interview_id.id
        join
            readside.interviewsummaries as summary
        on
            interview_id.interviewid = summary.interviewid
        join
            readside.questionnaire_entities as question_entity
        on
            interview.entityid = question_entity.id
        where
            extract(month from summary.updatedate) = :month and
            question_entity.stata_export_caption is not null and
            summary.questionnaireidentity = :questionnaire_id
        limit 100
        ';

        $stmt = $conn->prepare($query);
        $stmt->execute([
            'questionnaire_id' => $questionnaireId,
            'month' => $month,
            'no_answer' => $noAnswer
        ]);

        return $stmt->fetchAll();
    }
}