<?php

namespace App\Controller;

use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/users/{page}", name="user_management", requirements={"page"="\d+"})
     */
    public function userManagement(UserManager $manager, $page = 1)
    {
        $limit = 10;
        $users = $manager->getUsersList($page, $limit);
        $totalPages = ceil($users->count() / $limit);

        return $this->render('security/users.html.twig', [
            'users' => $users,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
    }
}
