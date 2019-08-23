<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('task_list');
        } else {
            return $this->redirectToRoute('login');
        }

    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        //Disalow connection for ANONYMOUS User
        $securityContext = $this->container->get('security.authorization_checker');
        /** @var User $user */
        $user = $this->getUser();
        if ($user && $securityContext->isGranted(Role::ANONYMOUS)) return $this->redirectToRoute('logout');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }
}
