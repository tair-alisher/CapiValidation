<?php

namespace App\Controller;

use App\Entity\Main\QuestionnaireValidation;
use App\Service\Getter;
use App\Form\ValidateType;
use App\Service\Validator;
use App\Entity\Main\Validation;
use App\Form\CreateValidationType;
use App\Repository\Main\ValidationRepository;
use App\Repository\Remote\InterviewRepository;
use App\Repository\Remote\QuestionnaireRepository as QuestRepo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ValidationController extends AbstractController
{
    /**
     * @Route("/validation/{page}", name="validation", requirements={"page"="\d+"}, methods={"GET", "POST"})
     */
    public function index(ValidationRepository $validationRepository, Request $request, $page = 1)
    {
        $limit = 10;
        $searchValue = $request->request->get('value');
        if ($searchValue) {
            $validations = $validationRepository->getAllWithNameByPages($searchValue, $page, $limit);
        } else {
            $validations = $validationRepository->getAllByPages($page, $limit);
            $searchValue = '';
        }
        $totalPages = ceil($validations->count() / $limit);

        return $this->render('validation/index.html.twig', [
            'validations' => $validations,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'searchValue' => $searchValue
        ]);
    }

    /**
     * @Route("/validation/{id}/details", name="validation.details", methods="GET")
     */
    public function details($id, ValidationRepository $validRepo, QuestRepo $questRepo)
    {
        $validation = $validRepo->find($id);

        $questionnairesId = $validRepo->getQuestionnairesIdForValidation($id);
        $belongsToQuestionnaires = $questRepo->getQuestionnairesByIds($questionnairesId);


        return $this->render('validation/details.html.twig', [
            'validation' => $validation,
            'questionnaires' => $belongsToQuestionnaires
        ]);
    }

    /**
     * @Route("/validation/create", name="validation.create_form", methods="GET")
     */
    public function create_form(QuestRepo $questionnaireRepo,Getter $getter)
    {
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
    public function create(Request $request, Validator $validator)
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
            $response['message'] = 'Validation created successfully.';
        } catch (\Exception $e) {
            $response['message'] = 'ошибка: ' . $e->getMessage() . '. File: ' . $e->getFile() . '. Line: ' . $e->getLine();
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

        $comparedValueBlockId = 'compared-value-' . time();

        return $this->render('validation/compared_value.html.twig', [
            'logic_operators' => $logicOperators,
            'compared_value_types' => $comparedValueTypes,
            'compared_value_block_id' => $comparedValueBlockId
        ]);
    }

    /**
     * @Route("/validation/add-questionnaire", name="validation.add_questionnaire", methods="POST")
     */
    public function addQuestionnaire(QuestRepo $questRepo)
    {
        $questionnaires = $questRepo->getTitleIdArray();

        $questionnaireBlockId = 'questionnaire-' . time();

        return $this->render('validation/questionnaire.html.twig', [
            'questionnaires' => $questionnaires,
            'questionnaire_block_id' => $questionnaireBlockId
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
     * @Route("/validation/rename")
     */
    public function rename(Request $request, Validator $validator)
    {
        $validationId = $request->request->get('validationId');
        $name = $request->request->get('name');

        $response = ['success' => true, 'message' => ''];

        try {
            $validator->renameValidation($validationId, $name);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/validation/detach")
     */
    public function detach(Request $request, Validator $validator)
    {
        $validationId = $request->request->get('validationId');
        $questionnaireId = $request->request->get('questionnaireId');

        $response = ['success' => true, 'message' => ''];

        try {
            $validator->detachValidation($validationId, $questionnaireId);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/validation/get-questionnaires-list")
     */
    public function getQuestionnairesList(QuestRepo $questRepo)
    {
        $questionnaires = $questRepo->getTitleIdArray();

        return $this->render('validation/questionnaire_list.html.twig', [
            'questionnaires' => $questionnaires
        ]);
    }

    /**
     * @Route("/validation/attach")
     */
    public function attach(QuestRepo $questRepo, Request $request, Validator $validator)
    {
        $validationId = $request->request->get('validationId');
        $questionnaireId = $request->request->get('questionnaireId');

        if ($validator->questionnaireValidationAlreadyExists($validationId, $questionnaireId)) {
           return new Response('already_exists');
        }

        $validator->attachValidation($validationId, $questionnaireId);
        $questionnaire = $questRepo->find($questionnaireId);

        return $this->render('validation/attached_questionnaire.html.twig', [
            'questionnaire' => $questionnaire
        ]);
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
     * @Route("/validate", name="validate", methods="GET")
     */
    public function validate(QuestRepo $questionnaireRepo)
    {
        $form = $this->createForm(ValidateType::class, null, [
            'questionnaire_repository' => $questionnaireRepo
        ]);

        return $this->render('validation/validate.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/validate/start", name="validate.start", methods="POST")
     */
    public function startValidate(Request $request, Validator $validator, InterviewRepository $inRepo)
    {
        $content = $request->getContent();
        $data = json_decode($content);

        $response = array(
            'completed' => false,
            'allRowsCount' => 0
        );

        $questionnaireId = $data->questionnaireId;

        if ($validator->validate($questionnaireId, $data->offset, $data->deleteCurrentErrors)) {
            $response['completed'] = true;
            return new JsonResponse($response);
        }

        $response['allRowsCount'] = $inRepo->getAllRowsCountByQuestionnaireId($questionnaireId);

        return new JsonResponse($response);
    }
}
