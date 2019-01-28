with interviewid as (
select
	int_id.interviewid as id
from
	readside.interviews_id as int_id
join
	readside.interviews as int
on
	int_id.id = int.interviewid
join
	readside.questionnaire_entities as ques_ent
on
	int.entityid = ques_ent.id
join
	plainstore.questionnairebrowseitems as ques_info
on
	ques_ent.questionnaireidentity = ques_info.id
where
	ques_ent.stata_export_caption = 'hhCode' and
	int.asint = 80645 and
	ques_info.title  = 'KIHS_Household_2018_November'
limit 1
)
-- select * from interviewid

select ques_info.title as опросник,
		summary.summaryid as ид_интервью,
		summary.key as идентификатор,
		summary.assignmentid as назначение,
		summary.teamleadname as супервизор,
		summary.responsiblename as ответственный,
		summary.updatedate as дата,
		ques_ent.stata_export_caption as ид_вопроса,
		ques_ent.question_text as вопрос,
		interview.interviewid,
		interview.entityid,
		int_id.interviewid,
		interview.asstring,
		interview.asint,
		interview.aslong,
		interview.asdouble,
		interview.asdatetime,
		interview.aslist,
		interview.asbool,
		interview.asintarray,
		interview.asintmatrix,
		interview.asgps,
		interview.asyesno,
		interview.asaudio,
		interview.asarea
from
	readside.interviews as interview
join
	readside.interviews_id as int_id
on
	interview.interviewid = int_id.id
join
	readside.questionnaire_entities as ques_ent
on
	interview.entityid = ques_ent.id
join
	plainstore.questionnairebrowseitems as ques_info
on
	ques_ent.questionnaireidentity = ques_info.id
join
	readside.interviewsummaries as summary
on
	int_id.interviewid = summary.interviewid
where
	int_id.interviewid = (select id from interviewid) and summary.wascompleted = true
limit
	100



select
	summary.summaryid as interviewid,
	question_entity.stata_export_caption as questionid,
	interview.asstring,
	interview.asint,
	interview.aslong,
	interview.asdouble,
	interview.asdatetime,
	interview.aslist,
	interview.asbool,
	interview.asintarray,
	interview.asintmatrix,
	interview.asgps,
	interview.asyesno,
	interview.asaudio,
	interview.asarea
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
where extract(month from timestamp summary.updatedate) = 11
limit 100