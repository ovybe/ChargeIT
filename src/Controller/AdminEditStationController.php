<?php

namespace App\Controller;

use App\Entity\Plug;
use App\Entity\Station;
use App\Form\AdminEditStationType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class AdminEditStationController extends AbstractController
{
    #[Route('/admin/edit/station/{uuid}', name: 'app_admin_edit_station')]
    public function update(Request $request,ManagerRegistry $doctrine, string $uuid): Response
    {
        $entityManager = $doctrine->getManager();
        $stationrepo = $entityManager->getRepository(Station::class);
        $station = $stationrepo->findOneBy(['uuid' => $uuid ]);

        $plugsrepo= $entityManager->getRepository(Plug::class);
        $plugs = $plugsrepo->findBy(['station'=>$station->getId()]);

        if (!$station) {
            throw $this->createNotFoundException(
                'No station found for id '.$uuid
            );
        }

        $form = $this->createForm(AdminEditStationType::class, $station);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chargeit_main_page');
        }

        return $this->renderForm('admin_edit_station/index.html.twig', [
            'controller_name' => 'CreateStationController',
            'form' => $form,
            'station' => $station,
            'plug' => $plugs,
        ]);
    }
    #[Route('/admin/delete/station/{uuid}', name: 'app_admin_delete_station')]
    public function delete(Request $request,ManagerRegistry $doctrine, string $uuid): Response
    {
        $entityManager = $doctrine->getManager();
        $stationrepo = $entityManager->getRepository(Station::class);
        $station = $stationrepo->findOneBy(['uuid' => $uuid ]);

        $plugsrepo= $entityManager->getRepository(Plug::class);
        $plugs = $plugsrepo->findBy(['station'=>$station->getId()]);

        if (!$station) {
            return $this->redirectToRoute('app_chargeit_main_page');
        }
        if(count($plugs)>0){
        foreach($plugs as $p){
            $entityManager->remove($p);
            $entityManager->flush();
        }
        }
        $entityManager->remove($station);
        $entityManager->flush();
        return $this->redirectToRoute('app_chargeit_main_page');
    }

}
