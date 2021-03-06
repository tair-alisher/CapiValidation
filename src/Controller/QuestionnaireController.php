<?php

namespace App\Controller;

use App\Entity\Main\ValidationError;
use App\Entity\Remote\Questionnaire;
use App\Repository\Main\ValidationErrorRepository as ErrorRepo;
use App\Repository\Remote\QuestionnaireRepository as QuestRepo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
    public function validationErrors($id, $page = 1, ErrorRepo $errorRepository, QuestRepo $questRepo)
    {
        $limit = 10;
        $errors = $errorRepository->getAllByQuestionnaireId($id, $page, $limit);
        $totalPages = ceil($errors->count() / $limit);
        $questionnaireTitle = $questRepo->find($id)->getTitle();

        return $this->render('questionnaire/check_errors.html.twig', [
            'errors' => $errors,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'id' => $id,
            'title' => $questionnaireTitle
        ]);
    }

    /**
     * @Route("/questionnaires/errors/delete")
     */
    public function deleteError(Request $request)
    {
        $errorId = $request->request->get('id');
        $response = ['success' => true, 'message' => ''];

        try {
            $em = $this->getDoctrine()->getManager();
            $error = $em->getRepository(ValidationError::class)->find($errorId);
            if ($error != null) {
                $em->remove($error);
                $em->flush();
            }
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return new JsonResponse($response);
    }
}
