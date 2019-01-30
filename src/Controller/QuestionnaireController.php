<?php

namespace App\Controller;

use App\Entity\Remote\Questionnaire;
use App\Repository\Main\CheckErrorRepository;
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
     * @Route("/questionnaire/{id}/errors", name="questionnaire.errors")
     */
    public function checkErrors(CheckErrorRepository $errorRepository, $id)
    {
        $errors = $errorRepository->getAllByQuestionnaireId($id);

        return $this->render('questionnaire/check_errors.html.twig', [
            'errors' => $errors
        ]);
    }
}
