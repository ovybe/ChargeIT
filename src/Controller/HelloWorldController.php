<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HelloWorldController extends AbstractController
{


     #[Route("/helloworld", "Hello world")]


    public function helloworld(){
        $userName='Ovi';
        return $this->render('helloworld.html.twig',['name'=> $userName,]);
    }

    #HW: Database, Entities.
}