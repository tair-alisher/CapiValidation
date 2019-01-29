<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\Main\ValidationRepository;
use App\Repository\Main\RestraintRepository;
use App\Repository\Remote\QuestionnaireRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ValidateType;
use App\Form\CreateValidationType;
use App\Entity\Main\Validation;

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
        ValidationRepository $validationRepository,
        QuestionnaireRepository $questionnaireRepository,
        RestraintRepository $restraintRepository)
    {
        $validation = new Validation();
        $form = $this->createForm(CreateValidationType::class, $validation, [
            'restraint_repository' => $restraintRepository,
            'questionnaire_repository' => $questionnaireRepository
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            var_dump($data);
            return;

            $questionnaire_title = $questionnaireRepository
                ->find($data->getQuestionnaireId())
                ->getTitle();
            $validation->setQuestionnaireTitle($questionnaire_title);

            $entityManager = $this->getDoctrine()->getManager('default');
            $entityManager->persist($validation);
            $entityManager->flush();

            return $this->redirectToRoute('validation');
        }

        return $this->render('validation/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/validation/test", name="validation.test")
     */
    public function test(RestraintRepository $restraintRepository)
    {
        $restraints = $restraintRepository->findAll();

        return $this->render('validation/test.html.twig', [
            'restraints' => $restraints
        ]);
    }

    /**
     * @Route("/validate", name="validate", methods={"GET", "POST"})
     */
    public function validate(Request $request, QuestionnaireRepository $questionnaireRepository)
    {
        $form = $this->createForm(ValidateType::class, null, [
            // 'action' => $this->generateUrl('validate'),
            // 'method' => 'POST',
            'questionnaire_repository' => $questionnaireRepository
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $data['quarter'];
        }

        return $this->render('validation/validate.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
