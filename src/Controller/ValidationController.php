<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\Main\ValidationRepository;
use App\Repository\Remote\QuestionnaireRepository;
use App\Repository\Main\InputValueTypeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ValidateType;
use App\Form\CreateValidationType;
use App\Entity\Main\Validation;
use App\Service\Validator;
use App\Service\Getter;

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
     * @Route("/validation/create", name="validation.create", methods={"GET", "POST"})
     */
    public function create(
        Request $request,
        QuestionnaireRepository $questionnaireRepo,
        Getter $getter,
        Validator $validator)
    {
        $validation = new Validation();
        $form = $this->createForm(CreateValidationType::class, null, [
            'getter' => $getter,
            'questionnaire_repository' => $questionnaireRepo
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($validator->createValidation($validation)) {
                return $this->redirectToRoute('validation');
            }
        }

        return $this->render('validation/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/validation/test", name="validation.test")
     */
    public function test(Getter $getter)
    {
        $types = $getter->getInputValueTypes();


        return $this->render('validation/test.html.twig', [
            'types' => $types
        ]);
    }

    /**
     * @Route("/validate", name="validate", methods={"GET", "POST"})
     */
    public function validate(Request $request, QuestionnaireRepository $questionnaireRepo, Validator $validator)
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
        }

        return $this->render('validation/validate.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
