<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Remote\Questionnaire;

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
        $currentPage = $page;

        return $this->render('questionnaire/index.html.twig', [
            'questionnaires' => $questionnaires,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage
        ]);
    }
}
