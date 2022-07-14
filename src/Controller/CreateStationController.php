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

    public function create_station(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->renderForm('admin/admin_create_station.html.twig', [
            // insert stuff here if you want
        ]);
    }
}
