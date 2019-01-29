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
	--distinct(summary.summaryid) as interviewid,
	--count(summary.summaryid)
	summary.summaryid as interviewid,
	summary.questionnaireidentity as questionnaire,
	question_entity.question_text as question,
	question_entity.stata_export_caption as questionid,
	coalesce(
		interview.asstring,
		cast(interview.asint as varchar),
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
		'нет ответа'
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
	extract(month from summary.updatedate) = 11 and
	question_entity.stata_export_caption is not null and
	summary.questionnaireidentity = 'a9320de7079d4a3795cde101f06bc2e2$2'
limit 100



insert into public.restraint(id, title, value) values('0906df78-1e78-40a0-9d43-c4e56b588b03', 'равно' ,'=');
insert into public.restraint(id, title, value) values('5b86ce6c-f6a7-47f1-90c3-f19a85981a46', 'больше', '>');
insert into public.restraint(id, title, value) values('ea33e523-fd92-4f4e-b185-f68693361e5b', 'меньше', '<');
insert into public.restraint(id, title, value) values('3e02b849-aaa5-43bb-9cc6-e3d951867deb', 'не', '!');
insert into public.restraint(id, title, value) values('2baf7693-d4f1-43ff-b697-1aaf6b45742c', 'больше или равно', '>=');
insert into public.restraint(id, title, value) values('d66b8364-7e1d-4170-9a4f-b873baa504e5', 'меньше или равно', '<=');