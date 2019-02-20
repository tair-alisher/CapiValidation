<?php

namespace App\Repository\Remote;

use App\Entity\Remote\Interview;
use App\Structure\QuestionData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * InterviewRepository
 */
class InterviewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Interview::class);
    }

    public function getInterviewsByQuestionnaireIdAndMonth($questionnaireId, $month, $offset, $limit): array
    {
        $conn = $this->getEntityManager('server')->getConnection();

        $query = '
        select
            summary.summaryid as interview_id,
            summary.questionnaireidentity as questionnaire_id,
            question_entity.stata_export_caption as question,
            question_entity.parentid as section,
			interview.rostervector as _id,
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
            summary.questionnaireidentity = :questionnaire_id and
            summary.wasrejectedbysupervisor = false
        offset :offset limit :limit;
        ';

        $stmt = $conn->prepare($query);
        $stmt->execute([
            'questionnaire_id' => $questionnaireId,
            'month' => $month,
            'no_answer' => "''",
            'offset' => $offset,
            'limit' => $limit
        ]);

        $rows = $stmt->fetchAll();

        $interviews = [];
        foreach ($rows as $row) {
            $filterResults = array_filter($interviews, function ($_interview) use ($row) {
                return $_interview->getInterviewId() == $row['interview_id'];
            });
            if (count($filterResults) > 0) {
                $interview = array_shift($filterResults);
                $sectionId = strlen($row['_id']) > 0 ? $row['section'] . '_' . $row['_id'] : $row['section'];
                $questionData = new QuestionData($sectionId, $row['question'], $row['answer']);
                $interview->pushToQuestionsData($questionData);
            } else {
                $interview = new Interview();
                $interview->setInterviewId($row['interview_id']);
                $interview->setQuestionnaireId($row['questionnaire_id']);
                $sectionId = strlen($row['_id']) > 0 ? $row['section'] . '_' . $row['_id'] : $row['section'];
                $questionData = new QuestionData($sectionId, $row['question'], $row['answer']);
                $interview->pushToQuestionsData($questionData);

                array_push($interviews, $interview);
            }
        }

        return $interviews;
    }

    public function getQuestionAnswer(string $interviewId, string $questionCode): ?string
    {
        $em = $this->getEntityManager('server');

        $query = '
        select
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
            ) as answer
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
          question_entity.stata_export_caption = :questionCode and
          summary.interviewid = :interviewId
        limit 1';

        $statement = $em->getConnection()->prepare($query);
        $statement->execute([
            'no_answer' => "''",
            'interviewId' => $interviewId,
            'questionCode' => $questionCode
        ]);

        $row = $statement->fetch();

        return $row['answer'];
    }
}