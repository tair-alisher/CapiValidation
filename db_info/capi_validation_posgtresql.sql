CREATE TABLE "public__answer_indicator" (
  "id" uuid[not_null],
  "title" varchar[50][not_null],
  "name" varchar[50][not_null]
);

CREATE TABLE "public__check_error" (
  "id" uuid[not_null],
  "interview_id" varchar[100][not_null],
  "questionnaire_id" varchar[100][not_null],
  "description" text[not_null]]
);

CREATE TABLE "public__compare_operator" (
  "id" uuid[not_null],
  "operator_value_id" uuid[not_null]
);

CREATE TABLE "public__compared_value" (
  "id" uuid[not_null],
  "validation_id" uuid[not_null],
  "c_value_type_id" uuid[not_null],
  "c_value" varchar[100][nullable],
  "c_operator_id" uuid[not_null],
  "logic_operator" uuid[nullable],
  "in_same_section" boolean[nullable]
);

CREATE TABLE "public__compared_value_type" (
  "id" uuid[not_null],
  "value_type_id" uuid[not_null]
);

CREATE TABLE "public__input_value_type" (
  "id" uuid[not_null],
  "value_type_id" uuid[not_null]
);

CREATE TABLE "public__logic_operator" (
  "id" uuid[not_null],
  "operator_value_id" uuid[not_null]
);

CREATE TABLE "public__operator_value" (
  "id" uuid[not_null],
  "title" varchar[50][not_null],
  "name" varchar[50][not_null]
);

CREATE TABLE "public__questionnaire_validation" (
  "id" uuid[not_null],
  "validation_id" uuid[not_null],
  "questionnaire_id" uuid[not_null]
);

CREATE TABLE "public__validation" (
  "id" uuid[not_null],
  "title" text[not_null],
  "answer_code" varchar[100][not_null],
  "answer_type_id" uuid[not_null],
  "answer_indicator_id" uudi[not_null],
  "rel_answer_code" varchar[100][nullable],
  "rel_answer_type_id" uuid[nullable],
  "rel_answer_value" varchar[100][nullable],
  "rel_answer_compare_operator_id" uuid[nullable],
  "in_same_section" boolean[nullable]
);

CREATE TABLE "public__value_type" (
  "id" uuid[not_null],
  "title" varchar[50][not_null],
  "name" varchar[50][not_null]
);

ALTER TABLE "public__compare_operator" ADD FOREIGN KEY ("operator_value_id") REFERENCES "public__operator_value" ("id");

ALTER TABLE "public__compared_value" ADD FOREIGN KEY ("c_operator_id") REFERENCES "public__compare_operator" ("id");

ALTER TABLE "public__compared_value" ADD FOREIGN KEY ("c_value_type_id") REFERENCES "public__compared_value_type" ("id");

ALTER TABLE "public__compared_value" ADD FOREIGN KEY ("validation_id") REFERENCES "public__validation" ("id");

ALTER TABLE "public__compared_value_type" ADD FOREIGN KEY ("value_type_id") REFERENCES "public__value_type" ("id");

ALTER TABLE "public__input_value_type" ADD FOREIGN KEY ("value_type_id") REFERENCES "public__value_type" ("id");

ALTER TABLE "public__logic_operator" ADD FOREIGN KEY ("operator_value_id") REFERENCES "public__operator_value" ("id");

ALTER TABLE "public__validation" ADD FOREIGN KEY ("answer_indicator_id") REFERENCES "public__answer_indicator" ("id");

ALTER TABLE "public__validation" ADD FOREIGN KEY ("answer_type_id") REFERENCES "public__input_value_type" ("id");

ALTER TABLE "public__validation" ADD FOREIGN KEY ("rel_answer_compare_operator_id") REFERENCES "public__compare_operator" ("id");

ALTER TABLE "public__validation" ADD FOREIGN KEY ("rel_answer_type_id") REFERENCES "public__compared_value_type" ("id");