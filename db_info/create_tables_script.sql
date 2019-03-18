-- answer_indicator table
CREATE TABLE public.answer_indicator
(
  id uuid NOT NULL,
  title character varying(50) COLLATE pg_catalog."default" NOT NULL,
  name character varying(50) COLLATE pg_catalog."default" NOT NULL,
  CONSTRAINT answer_indicator_pkey PRIMARY KEY (id)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.answer_indicator
  OWNER to postgres;


-- check_error table
CREATE TABLE public.check_error
(
  id uuid NOT NULL,
  interview_id character varying(100) COLLATE pg_catalog."default" NOT NULL,
  questionnaire_id character varying(100) COLLATE pg_catalog."default" NOT NULL,
  description text COLLATE pg_catalog."default" NOT NULL,
  CONSTRAINT check_error_pkey PRIMARY KEY (id)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.check_error
  OWNER to postgres;


-- compare_operator table
CREATE TABLE public.compare_operator
(
  id uuid NOT NULL,
  operator_value_id uuid NOT NULL,
  CONSTRAINT compare_operator_pkey PRIMARY KEY (id),
  CONSTRAINT operator_value_id FOREIGN KEY (operator_value_id)
  REFERENCES public.operator_value (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.compare_operator
  OWNER to postgres;


-- compared_value table
CREATE TABLE public.compared_value
(
  id uuid NOT NULL,
  validation_id uuid NOT NULL,
  c_value_type_id uuid NOT NULL,
  c_value character varying(100) COLLATE pg_catalog."default",
  c_operator_id uuid NOT NULL,
  logic_operator_id uuid,
  in_same_section boolean,
  CONSTRAINT compared_value_pkey PRIMARY KEY (id),
  CONSTRAINT compared_value_c_operator_id__compare_operator_id FOREIGN KEY (c_operator_id)
  REFERENCES public.compare_operator (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  CONSTRAINT compared_value_c_value_type_id__compared_value_type_id FOREIGN KEY (c_value_type_id)
  REFERENCES public.compared_value_type (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  CONSTRAINT compared_value_validation_id__validation_id FOREIGN KEY (validation_id)
  REFERENCES public.validation (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.compared_value
  OWNER to postgres;


-- compared_value_type
CREATE TABLE public.compared_value_type
(
  id uuid NOT NULL,
  value_type_id uuid NOT NULL,
  CONSTRAINT compared_value_type_pkey PRIMARY KEY (id),
  CONSTRAINT value_type_id FOREIGN KEY (value_type_id)
  REFERENCES public.value_type (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.compared_value_type
  OWNER to postgres;


-- input_value_type table
CREATE TABLE public.input_value_type
(
  id uuid NOT NULL,
  value_type_id uuid NOT NULL,
  CONSTRAINT input_value_type_pkey PRIMARY KEY (id),
  CONSTRAINT value_type_id FOREIGN KEY (value_type_id)
  REFERENCES public.value_type (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE CASCADE
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.input_value_type
  OWNER to postgres;


-- logic_operator
CREATE TABLE public.logic_operator
(
  id uuid NOT NULL,
  operator_value_id uuid NOT NULL,
  CONSTRAINT logic_operator_pkey PRIMARY KEY (id),
  CONSTRAINT operator_value_id FOREIGN KEY (operator_value_id)
  REFERENCES public.operator_value (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.logic_operator
  OWNER to postgres;


-- operator_value table
CREATE TABLE public.operator_value
(
  id uuid NOT NULL,
  title character varying(50) COLLATE pg_catalog."default" NOT NULL,
  name character varying(50) COLLATE pg_catalog."default" NOT NULL,
  CONSTRAINT operator_value_pkey PRIMARY KEY (id)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.operator_value
  OWNER to postgres;


-- questionnaire_validation table
CREATE TABLE public.questionnaire_validation
(
  id uuid NOT NULL,
  validation_id uuid NOT NULL,
  questionnaire_id character varying(100) COLLATE pg_catalog."default" NOT NULL,
  CONSTRAINT questionnaire_validation_pkey PRIMARY KEY (id)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.questionnaire_validation
  OWNER to postgres;


-- role table
CREATE TABLE public.role
(
  id integer NOT NULL,
  name character varying(30) COLLATE pg_catalog."default" NOT NULL,
  title character varying(100) COLLATE pg_catalog."default" NOT NULL,
  CONSTRAINT role_pkey PRIMARY KEY (id)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.role
  OWNER to postgres;

-- insert roles
INSERT INTO public.role(id, name, title)
VALUES (1, 'ROLE_USER', 'пользователь');
INSERT INTO public.role(id, name, title)
VALUES (2, 'ROLE_ADMIN', 'администратор');


-- user table
CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1;

CREATE TABLE public."user"
(
  id integer NOT NULL,
  username character varying(180) COLLATE pg_catalog."default" NOT NULL,
  roles json,
  password character varying(250) COLLATE pg_catalog."default" NOT NULL,
  CONSTRAINT user_pkey PRIMARY KEY (id)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public."user"
  OWNER to postgres;


-- validation table
CREATE TABLE public.validation
(
  id uuid NOT NULL,
  title text COLLATE pg_catalog."default" NOT NULL,
  answer_code character varying(100) COLLATE pg_catalog."default" NOT NULL,
  answer_type_id uuid NOT NULL,
  answer_indicator_id uuid NOT NULL,
  rel_answer_code character varying(100) COLLATE pg_catalog."default",
  rel_answer_type_id uuid,
  rel_answer_value character varying(100) COLLATE pg_catalog."default",
  rel_answer_compare_operator_id uuid,
  in_same_section boolean,
  CONSTRAINT validation_pkey PRIMARY KEY (id),
  CONSTRAINT answer_indicator_type_id FOREIGN KEY (answer_indicator_id)
  REFERENCES public.answer_indicator (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  CONSTRAINT answer_type_id FOREIGN KEY (answer_type_id)
  REFERENCES public.input_value_type (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  CONSTRAINT rel_answer_compare_operator_id FOREIGN KEY (rel_answer_compare_operator_id)
  REFERENCES public.compare_operator (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION,
  CONSTRAINT rel_answer_type_id FOREIGN KEY (rel_answer_type_id)
  REFERENCES public.compared_value_type (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.validation
  OWNER to postgres;


-- value_type table
CREATE TABLE public.value_type
(
  id uuid NOT NULL,
  title character varying(50) COLLATE pg_catalog."default" NOT NULL,
  name character varying(50) COLLATE pg_catalog."default" NOT NULL,
  CONSTRAINT value_type_pkey PRIMARY KEY (id)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.value_type
  OWNER to postgres;