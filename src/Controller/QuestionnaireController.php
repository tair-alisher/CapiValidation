<?php

namespace App\Controller;

use App\Entity\Remote\Questionnaire;
use App\Repository\Main\ValidationErrorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class QuestionnaireController extends AbstractController
{
    /**
     * @Route("/questionnaires/{page}", name="questionnaires", requirements={"page"="\d+"})
     */
    public function index($page = 1)
    {
        $limit = 15;
        $questionnaires = $this->getDoctrine()
            ->getRepository(Questionnaire::class, 'server')
            ->getAllQuestionnaires($page, $limit);
        $totalPages = ceil($questionnaires->count() / $limit);

        return $this->render('questionnaire/index.html.twig', [
            'questionnaires' => $questionnaires,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
    }

    /**
     * @Route("/questionnaire/{id}/errors/{page}", name="questionnaire.errors")
     */
    public function validationErrors(ValidationErrorRepository $errorRepository, $id, $page)
    {
        $limit = 10;
        $errors = $errorRepository->getAllByQuestionnaireId($id, $page, $limit);
        $totalPages = ceil($errors->count() / $limit);

        return $this->render('questionnaire/check_errors.html.twig', [
            'errors' => $errors,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
    }
}
