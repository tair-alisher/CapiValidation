<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\Remote\QuestionnaireRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ValidationType;

class ValidationController extends AbstractController
{
    /**
     * @Route("/validation", name="validation", methods={"GET", "POST"})
     */
    public function index(Request $request, QuestionnaireRepository $questionnaireRepository)
    {
        $form = $this->createForm(ValidationType::class, null, [
            // 'action' => $this->generateUrl('validate'),
            // 'method' => 'POST',
            'questionnaire_repository' => $questionnaireRepository
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $data['quarter'];
        }

        return $this->render('validation/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
