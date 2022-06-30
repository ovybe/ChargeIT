<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]

    public function index(Request $request,ManagerRegistry $doctrine,AuthenticationUtils $authenticationUtils): Response
    {


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        //dd($error);

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->renderForm('login/index.html.twig', [
                         'controller_name' => 'LoginController',
                         'last_username' => $lastUsername,
                         'error'         => $error,
        ]);
//        return $this->render('login/index.html.twig', [
//            'controller_name' => 'LoginController',
//        ]);
    }
}
