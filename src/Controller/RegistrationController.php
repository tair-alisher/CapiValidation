<?php

namespace App\Controller;

use App\Entity\Main\User;
use App\Form\RegistrationFormType;
use App\Repository\Main\RoleRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class RegistrationController
 * @package App\Controller
 *
 * @IsGranted("ROLE_USER")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, RoleRepository $roleRepo): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'attr' => ['class' => ' col-md-6 mx-auto login-form']
        ]);
        $roles = $roleRepo->getAllOrderedByTitle();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $selectedRole = $roleRepo->find($request->request->get('role'))->getName();
            $user->setRoles([$selectedRole]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

//            return $guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $authenticator,
//                'main' // firewall name in security.yaml
//            );

            return $this->redirectToRoute('user_management');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'roles' => $roles
        ]);
    }
}
