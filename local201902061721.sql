--
-- PostgreSQL database dump
--

-- Dumped from database version 11.1
-- Dumped by pg_dump version 11.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.compared_value_type DROP CONSTRAINT IF EXISTS value_type_id;
ALTER TABLE IF EXISTS ONLY public.input_value_type DROP CONSTRAINT IF EXISTS value_type_id;
ALTER TABLE IF EXISTS ONLY public.validation DROP CONSTRAINT IF EXISTS rel_answer_type_id;
ALTER TABLE IF EXISTS ONLY public.validation DROP CONSTRAINT IF EXISTS rel_answer_compare_operator_id;
ALTER TABLE IF EXISTS ONLY public.logic_operator DROP CONSTRAINT IF EXISTS operator_value_id;
ALTER TABLE IF EXISTS ONLY public.compare_operator DROP CONSTRAINT IF EXISTS operator_value_id;
ALTER TABLE IF EXISTS ONLY public.compared_value DROP CONSTRAINT IF EXISTS compared_value_validation_id__validation_id;
ALTER TABLE IF EXISTS ONLY public.compared_value DROP CONSTRAINT IF EXISTS compared_value_c_value_type_id__compared_value_type_id;
ALTER TABLE IF EXISTS ONLY public.compared_value DROP CONSTRAINT IF EXISTS compared_value_c_operator_id__compare_operator_id;
ALTER TABLE IF EXISTS ONLY public.validation DROP CONSTRAINT IF EXISTS answer_type_id;
ALTER TABLE IF EXISTS ONLY public.validation DROP CONSTRAINT IF EXISTS answer_indicator_type_id;
ALTER TABLE IF EXISTS ONLY public.value_type DROP CONSTRAINT IF EXISTS value_type_pkey;
ALTER TABLE IF EXISTS ONLY public.validation DROP CONSTRAINT IF EXISTS validation_pkey;
ALTER TABLE IF EXISTS ONLY public.questionnaire_validation DROP CONSTRAINT IF EXISTS questionnaire_validation_pkey;
ALTER TABLE IF EXISTS ONLY public.operator_value DROP CONSTRAINT IF EXISTS operator_value_pkey;
ALTER TABLE IF EXISTS ONLY public.logic_operator DROP CONSTRAINT IF EXISTS logic_operator_pkey;
ALTER TABLE IF EXISTS ONLY public.input_value_type DROP CONSTRAINT IF EXISTS input_value_type_pkey;
ALTER TABLE IF EXISTS ONLY public.compared_value_type DROP CONSTRAINT IF EXISTS compared_value_type_pkey;
ALTER TABLE IF EXISTS ONLY public.compared_value DROP CONSTRAINT IF EXISTS compared_value_pkey;
ALTER TABLE IF EXISTS ONLY public.compare_operator DROP CONSTRAINT IF EXISTS compare_operator_pkey;
ALTER TABLE IF EXISTS ONLY public.check_error DROP CONSTRAINT IF EXISTS check_error_pkey;
ALTER TABLE IF EXISTS ONLY public.answer_indicator DROP CONSTRAINT IF EXISTS answer_indicator_pkey;
DROP TABLE IF EXISTS public.value_type;
DROP TABLE IF EXISTS public.validation;
DROP TABLE IF EXISTS public.questionnaire_validation;
DROP TABLE IF EXISTS public.operator_value;
DROP TABLE IF EXISTS public.logic_operator;
DROP TABLE IF EXISTS public.input_value_type;
DROP TABLE IF EXISTS public.compared_value_type;
DROP TABLE IF EXISTS public.compared_value;
DROP TABLE IF EXISTS public.compare_operator;
DROP TABLE IF EXISTS public.check_error;
DROP TABLE IF EXISTS public.answer_indicator;
SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: answer_indicator; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.answer_indicator (
    id uuid NOT NULL,
    title character varying(50) NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.answer_indicator OWNER TO postgres;

--
-- Name: check_error; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.check_error (
    id uuid NOT NULL,
    interview_id character varying(100) NOT NULL,
    questionnaire_id character varying(100) NOT NULL,
    description text NOT NULL
);


ALTER TABLE public.check_error OWNER TO postgres;

--
-- Name: compare_operator; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.compare_operator (
    id uuid NOT NULL,
    operator_value_id uuid NOT NULL
);


ALTER TABLE public.compare_operator OWNER TO postgres;

--
-- Name: compared_value; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.compared_value (
    id uuid NOT NULL,
    validation_id uuid NOT NULL,
    c_value_type_id uuid NOT NULL,
    c_value character varying(100),
    c_operator_id uuid NOT NULL,
    logic_operator_id uuid
);


ALTER TABLE public.compared_value OWNER TO postgres;

--
-- Name: compared_value_type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.compared_value_type (
    id uuid NOT NULL,
    value_type_id uuid NOT NULL
);


ALTER TABLE public.compared_value_type OWNER TO postgres;

--
-- Name: input_value_type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.input_value_type (
    id uuid NOT NULL,
    value_type_id uuid NOT NULL
);


ALTER TABLE public.input_value_type OWNER TO postgres;

--
-- Name: logic_operator; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.logic_operator (
    id uuid NOT NULL,
    operator_value_id uuid NOT NULL
);


ALTER TABLE public.logic_operator OWNER TO postgres;

--
-- Name: operator_value; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.operator_value (
    id uuid NOT NULL,
    title character varying(50) NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.operator_value OWNER TO postgres;

--
-- Name: questionnaire_validation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.questionnaire_validation (
    id uuid NOT NULL,
    questionnaire_id uuid NOT NULL,
    validation_id uuid NOT NULL
);


ALTER TABLE public.questionnaire_validation OWNER TO postgres;

--
-- Name: validation; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.validation (
    id uuid NOT NULL,
    title text NOT NULL,
    answer_code character varying(100) NOT NULL,
    answer_type_id uuid NOT NULL,
    answer_indicator_id uuid NOT NULL,
    rel_answer_code character varying(100),
    rel_answer_type_id uuid,
    rel_answer_value character varying(100),
    questionnaire_id character varying(100) NOT NULL,
    rel_answer_compare_operator_id uuid
);


ALTER TABLE public.validation OWNER TO postgres;

--
-- Name: value_type; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.value_type (
    id uuid NOT NULL,
    title character varying(50) NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.value_type OWNER TO postgres;

--
-- Data for Name: answer_indicator; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.answer_indicator (id, title, name) FROM stdin;
b1406ef4-b6ca-44e7-8bcb-3d7519f22503	длина	length
47b00be3-d66a-458e-803b-d7395bde95b0	значение	value
\.


--
-- Data for Name: check_error; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.check_error (id, interview_id, questionnaire_id, description) FROM stdin;
2e4e084a-75c1-4829-a256-49be48f2a90e	986a34f163c248d9a5b2066e8bf7ce2b	99ec076ca90a4bc09abbdca5a5c484e0$1	hhCode больше 20001
0de7fcb4-e596-456c-9d08-44e46feac051	ad23f24fc7ea4b66b845d84a2b82425c	dfb8314a433945ab8c70fc3763e563f3$1	hhCode больше 20001
\.


--
-- Data for Name: compare_operator; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.compare_operator (id, operator_value_id) FROM stdin;
48e18905-34a4-47ff-b7c7-a13bd59d0025	bb44b553-5403-423c-9f4e-54ad87aad118
3f2e9568-5156-467a-9c88-0aa36339f5b7	ca2ab5c8-a3c3-4972-9d68-71fe1344465d
35a843b3-704a-41b0-aa8b-9bc2b108cf5d	d17dcea1-28c9-45d8-bc6b-56d52838ad6a
bcea67ae-3768-4327-b0e8-1e1e9ae202a2	99340435-3b89-476c-9ed4-d3a49182a462
29990644-afcf-4f23-ad0b-d0fc33fc7418	454ca38d-e88a-4a16-9bf2-0884b08084d7
\.


--
-- Data for Name: compared_value; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.compared_value (id, validation_id, c_value_type_id, c_value, c_operator_id, logic_operator_id) FROM stdin;
\.


--
-- Data for Name: compared_value_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.compared_value_type (id, value_type_id) FROM stdin;
217480ef-0df2-45cf-a2c0-47a2ef4232f2	bd8b584f-1bcf-4e51-9407-eb8186d3a248
b9ff9486-6036-4a3e-9c9f-40398ab16676	5057e34b-c059-405f-898b-163610123c82
94d00d03-fea2-419b-b8e1-2de057a5dafe	72912b00-9f63-4de9-9c05-30ce06055bff
a7090024-c1ee-451f-8485-eea5f6e9299d	43b667ff-5f29-41c3-b539-d29b91a3d660
872a4c41-0c6e-4d05-aa81-d6ca79fdb802	9f80a2c9-e1be-4036-8475-4d49837dbdbd
\.


--
-- Data for Name: input_value_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.input_value_type (id, value_type_id) FROM stdin;
eb09a27e-f5f9-463a-a155-b389e9beade4	72912b00-9f63-4de9-9c05-30ce06055bff
12a033eb-fb68-4696-8606-c972accc7ea4	43b667ff-5f29-41c3-b539-d29b91a3d660
43e46de2-b090-4994-94ac-02a6b4fcabc7	e38f6cb5-9433-4c0d-95b1-21e6ede90d55
\.


--
-- Data for Name: logic_operator; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.logic_operator (id, operator_value_id) FROM stdin;
55d60e73-fefc-40cb-b750-55f55e5034c2	e7e8c37d-6b18-49b2-8036-139190771f8e
85dbfbab-036f-4ad9-8bcd-9c9904d79a87	719050e6-a500-4361-839f-e597218979f3
\.


--
-- Data for Name: operator_value; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.operator_value (id, title, name) FROM stdin;
e7e8c37d-6b18-49b2-8036-139190771f8e	и	&&
719050e6-a500-4361-839f-e597218979f3	или	||
bb44b553-5403-423c-9f4e-54ad87aad118	больше	>
ca2ab5c8-a3c3-4972-9d68-71fe1344465d	меньше	<
d17dcea1-28c9-45d8-bc6b-56d52838ad6a	не равно	!=
99340435-3b89-476c-9ed4-d3a49182a462	больше или равно	>=
454ca38d-e88a-4a16-9bf2-0884b08084d7	меньше или равно	<=
9ff3bc14-c5ae-42cd-9e85-582c7063d9c7	сумма	sum
\.


--
-- Data for Name: questionnaire_validation; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.questionnaire_validation (id, questionnaire_id, validation_id) FROM stdin;
\.


--
-- Data for Name: validation; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.validation (id, title, answer_code, answer_type_id, answer_indicator_id, rel_answer_code, rel_answer_type_id, rel_answer_value, questionnaire_id, rel_answer_compare_operator_id) FROM stdin;
\.


--
-- Data for Name: value_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.value_type (id, title, name) FROM stdin;
4a1192d9-d67a-4888-85d8-3beb82f0961d	дата	datetime
43b667ff-5f29-41c3-b539-d29b91a3d660	нецелое число	double
9f80a2c9-e1be-4036-8475-4d49837dbdbd	пусто	null
bd8b584f-1bcf-4e51-9407-eb8186d3a248	множество	plurality
5057e34b-c059-405f-898b-163610123c82	диапазон	range
69d1dbae-6030-477e-b4cc-f38d22132530	показатель	indicator
72912b00-9f63-4de9-9c05-30ce06055bff	целое число	integer
e38f6cb5-9433-4c0d-95b1-21e6ede90d55	строка	string
\.


--
-- Name: answer_indicator answer_indicator_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.answer_indicator
    ADD CONSTRAINT answer_indicator_pkey PRIMARY KEY (id);


--
-- Name: check_error check_error_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.check_error
    ADD CONSTRAINT check_error_pkey PRIMARY KEY (id);


--
-- Name: compare_operator compare_operator_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compare_operator
    ADD CONSTRAINT compare_operator_pkey PRIMARY KEY (id);


--
-- Name: compared_value compared_value_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compared_value
    ADD CONSTRAINT compared_value_pkey PRIMARY KEY (id);


--
-- Name: compared_value_type compared_value_type_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compared_value_type
    ADD CONSTRAINT compared_value_type_pkey PRIMARY KEY (id);


--
-- Name: input_value_type input_value_type_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.input_value_type
    ADD CONSTRAINT input_value_type_pkey PRIMARY KEY (id);


--
-- Name: logic_operator logic_operator_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logic_operator
    ADD CONSTRAINT logic_operator_pkey PRIMARY KEY (id);


--
-- Name: operator_value operator_value_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.operator_value
    ADD CONSTRAINT operator_value_pkey PRIMARY KEY (id);


--
-- Name: questionnaire_validation questionnaire_validation_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.questionnaire_validation
    ADD CONSTRAINT questionnaire_validation_pkey PRIMARY KEY (id);


--
-- Name: validation validation_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.validation
    ADD CONSTRAINT validation_pkey PRIMARY KEY (id);


--
-- Name: value_type value_type_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.value_type
    ADD CONSTRAINT value_type_pkey PRIMARY KEY (id);


--
-- Name: validation answer_indicator_type_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.validation
    ADD CONSTRAINT answer_indicator_type_id FOREIGN KEY (answer_indicator_id) REFERENCES public.answer_indicator(id);


--
-- Name: validation answer_type_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.validation
    ADD CONSTRAINT answer_type_id FOREIGN KEY (answer_type_id) REFERENCES public.input_value_type(id);


--
-- Name: compared_value compared_value_c_operator_id__compare_operator_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compared_value
    ADD CONSTRAINT compared_value_c_operator_id__compare_operator_id FOREIGN KEY (c_operator_id) REFERENCES public.compare_operator(id);


--
-- Name: compared_value compared_value_c_value_type_id__compared_value_type_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compared_value
    ADD CONSTRAINT compared_value_c_value_type_id__compared_value_type_id FOREIGN KEY (c_value_type_id) REFERENCES public.compared_value_type(id);


--
-- Name: compared_value compared_value_validation_id__validation_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compared_value
    ADD CONSTRAINT compared_value_validation_id__validation_id FOREIGN KEY (validation_id) REFERENCES public.validation(id);


--
-- Name: compare_operator operator_value_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compare_operator
    ADD CONSTRAINT operator_value_id FOREIGN KEY (operator_value_id) REFERENCES public.operator_value(id);


--
-- Name: logic_operator operator_value_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logic_operator
    ADD CONSTRAINT operator_value_id FOREIGN KEY (operator_value_id) REFERENCES public.operator_value(id);


--
-- Name: validation rel_answer_compare_operator_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.validation
    ADD CONSTRAINT rel_answer_compare_operator_id FOREIGN KEY (rel_answer_compare_operator_id) REFERENCES public.compare_operator(id);


--
-- Name: validation rel_answer_type_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.validation
    ADD CONSTRAINT rel_answer_type_id FOREIGN KEY (rel_answer_type_id) REFERENCES public.compared_value_type(id);


--
-- Name: input_value_type value_type_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.input_value_type
    ADD CONSTRAINT value_type_id FOREIGN KEY (value_type_id) REFERENCES public.value_type(id) ON DELETE CASCADE;


--
-- Name: compared_value_type value_type_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.compared_value_type
    ADD CONSTRAINT value_type_id FOREIGN KEY (value_type_id) REFERENCES public.value_type(id);


--
-- PostgreSQL database dump complete
--

