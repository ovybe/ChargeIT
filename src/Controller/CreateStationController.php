<?php

namespace App\Controller;

use App\Entity\Station;
use App\Form\CreateStationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateStationController extends AbstractController
{
    #[Route('/create/station', name: 'app_create_station')]
    public function index(Request $request,ManagerRegistry $doctrine): Response
    {
        $station = new Station();
        $form = $this->createForm(CreateStationType::class, $station);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $station->genUuid();
            $entityManager->persist($station);
            $entityManager->flush();

            return $this->redirectToRoute('app_chargeit_main_page');
        }

        return $this->renderForm('create_station/index.html.twig', [
            'controller_name' => 'CreateStationController',
            'form' => $form,
        ]);
    }
    public function create_station(): Response
    {
        return $this->renderForm('create_station/index.html.twig', [
            // insert stuff here if you want
        ]);
    }
}
