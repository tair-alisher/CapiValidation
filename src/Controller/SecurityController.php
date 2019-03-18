<?php

namespace App\Controller;

use App\Form\EditUserFormType;
use App\Repository\Main\RoleRepository;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
     * @IsGranted("ROLE_ADMIN")
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

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function userEdit($id, Request $request, UserManager $userManager, RoleRepository $roleRepo)
    {
        $user = $userManager->get($id);
        $form = $this->createForm(EditUserFormType::class, $user, [
            'attr' => ['class' => 'col-md-6 mx-auto login-form']
        ]);
        $userRole = $user->getRoles()[0];
        $allRoles = $roleRepo->getAllOrderedByTitle();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedRole = $roleRepo->find($request->request->get('role'))->getName();
            $user->setUsername($form->get('username')->getData());
            $user->setRoles([$selectedRole]);

            $this->getDoctrine()->getManager()->flush();
            $userRole = $user->getRoles()[0];
        }

        return $this->render('security/edit.html.twig', [
            'editForm' => $form->createView(),
            'userRole' => $userRole,
            'roles' => $allRoles
        ]);
    }

    /**
     * @Route("/users/{id}/delete", name="user_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function userDelete($id, Request $request, UserManager $userManager)
    {
        $userManager->remove($id);

        return $this->redirectToRoute('user_management');
    }
}
