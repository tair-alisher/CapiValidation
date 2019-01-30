<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Remote\Questionnaire;

class HomeController extends AbstractController
{
    /**
     * @Route("/home/{page}", name="home", requirements={"page"="\d+"})
     */
    public function index($page = 1)
    {
        $limit = 5;
        $questionnaires = $this->getDoctrine()
            ->getRepository(Questionnaire::class, 'server')
            // ->findAll();
            ->getAllQuestionnaires($page, $limit);

        $totalPages = ceil($questionnaires->count() / $limit);
        $currentPage = $page;

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'questionnaires' => $questionnaires,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage
        ]);
    }
}
