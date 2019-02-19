<?php

namespace App\Controller;

use App\Service\Getter;
use App\Form\ValidateType;
use App\Service\Validator;
use App\Entity\Main\Validation;
use App\Form\CreateValidationType;
use App\Repository\Main\ValidationRepository;
use App\Repository\Main\InputValueTypeRepository;
use App\Repository\Remote\QuestionnaireRepository as QuestRepo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use App\Repository\Remote\InterviewRepository;

class ValidationController extends AbstractController
{
    /**
     * @Route("/validation", name="validation", methods="GET")
     */
    public function index(ValidationRepository $validationRepository, $page = 1)
    {
        $limit = 10;
        $validations = $validationRepository->getAllByPages($page, $limit);
        $totalPages = ceil($validations->count() / $limit);

        return $this->render('validation/index.html.twig', [
            'validations' => $validations,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
    }

    /**
     * @Route("/validation/create", name="validation.create_form", methods="GET")
     */
    public function create_form(
        Request $request,
        QuestRepo $questionnaireRepo,
        Getter $getter,
        Validator $validator)
    {
        $validation = new Validation();
        $form = $this->createForm(CreateValidationType::class, null, [
            'action' => $this->generateUrl('validation.create'),
            'getter' => $getter,
            'questionnaire_repository' => $questionnaireRepo
        ]);

        return $this->render('validation/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/validation/create", name="validation.create", methods="POST")
     */
    public function create(Request $request, QuestRepo $questRepo, Validator $validator)
    {
        $content = $request->getContent();
        $_validation = json_decode($content);

        $response = array(
            'success' => false,
            'message' => ''
        );

        try {
            $validator->createValidation($_validation);
            $response['success'] = true;
            $response['message'] = 'Validation created successfuly.';
        } catch (\Exception $e) {
            $response['message'] = 'ошибка: ' . $e->getMessage();
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/validation/add-compared-value", name="validation.add_compared_value", methods="POST")
     */
    public function addComparedValue(Getter $getter)
    {
        $logicOperators = $getter->getLogicOperators();
        $comparedValueTypes = $getter->getComparedValueTypes();

        return $this->render('validation/compared_value.html.twig', [
            'logic_operators' => $logicOperators,
            'compared_value_types' => $comparedValueTypes
        ]);
    }

    /**
     * @Route("/validation/add-questionnaire", name="validation.add_questionnaire", methods="POST")
     */
    public function addQuestionnaire(QuestRepo $questRepo)
    {
        $questionnaires = $questRepo->getTitleIdArray();

        return $this->render('validation/questionnaire.html.twig', [
            'questionnaires' => $questionnaires
        ]);
    }

    /**
     * @Route("/validation/delete", name="validation.delete", methods="POST")
     */
    public function delete(Request $request, ValidationRepository $valRepo)
    {
        $response = array(
            'success' => false,
            'message' => ''
        );

        $id = $request->request->get('id');

        try {
            // delete validation's compared values
            $valRepo->removeComparedValuesByValidationId($id);

            // delete validation
            $em = $this->getDoctrine()->getManager();
            $validation = $em->getRepository(Validation::class)->find($id);
            if ($validation) {
                $em->remove($validation);
                $em->flush();
            }

            $response['success'] = true;
            $response['message'] = 'Контроль удален.';

            // delete validation from questionnaire's validation list
            $valRepo->removeValidationFromQuestionnaireValidationList($id);
        } catch (\Exception $e) {
            $response['message'] = 'ошибка: ' . $e->getMessage();
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/validation/test", name="validation.test")
     */
    public function test(Getter $getter)
    {
        $compareOperators = $getter->getCompareOperators();
        $comparedValueTypes = $getter->getComparedValueTypes();

        return $this->render('validation/test.html.twig', [
            'compare_operators' => $compareOperators,
            'compared_value_types' => $comparedValueTypes
        ]);
    }

    /**
     * @Route("/validate", name="validate", methods={"GET", "POST"})
     */
    public function validate(Request $request, QuestRepo $questionnaireRepo, Validator $validator)
    {
        $form = $this->createForm(ValidateType::class, null, [
            'questionnaire_repository' => $questionnaireRepo
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $questionnaireId = $form["questionnaire"]->getData();
            $quarter = $form["quarter"]->getData();
            $month = $form["month"]->getData();
            $area = $form["area"]->getData();

            if ($validator->validate($questionnaireId, $month)) {
                return $this->redirectToRoute('questionnaire.errors', ['id' => $questionnaireId]);
            }
//
//            $expression = $validator->validate($questionnaireId, $month);
//
//            return $this->render('validation/expression.html.twig', [
//                'expression' => $expression
//            ]);
        }

        return $this->render('validation/validate.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
