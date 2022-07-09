<?php

namespace App\Controller;

use App\Entity\Plug;
use App\Entity\Station;
use App\Form\AdminEditPlugType;
use App\Form\CreatePlugType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEditPlugController extends AbstractController
{
    // PUT MANAGER IN CONSTRUCTOR
    #[Route('/admin/edit/plug/{uuid}', name: 'app_admin_edit_plug')]
    public function update(Request $request,ManagerRegistry $doctrine, string $uuid): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager();

        $plugsrepo= $entityManager->getRepository(Plug::class);
        $plug = $plugsrepo->findOneBy(['id'=>$uuid]);

        if (!$plug) {
            throw $this->createNotFoundException(
                'No plug found for id '.$uuid
            );
        }

        $form = $this->createForm(AdminEditPlugType::class, $plug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $station=$plug->getStation();

            return $this->redirectToRoute('app_admin_edit_station',[
                'uuid' => $station->getUuid(),
            ]);
        }

        return $this->renderForm('admin_edit_plug/index.html.twig', [
            'form' => $form,
//            'controller_name' => 'AdminEditPlugController',
        ]);
    }

    #[Route('/admin/delete/plug/{uuid}', name: 'app_admin_delete_plug')]
    public function delete(Request $request,ManagerRegistry $doctrine, string $uuid): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager();
        $plugsrepo= $entityManager->getRepository(Plug::class);
        $plug = $plugsrepo->findOneBy(['id'=>$uuid]);

        $station = $plug->getStation();

        if (!$plug) {
            return $this->redirectToRoute('app_admin_edit_station',[
                'uuid' => $station->getUuid(),
            ]);
        }

        $entityManager->remove($plug);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_edit_station',[
            'uuid' => $station->getUuid(),
        ]);
    }
    #[Route('/admin/create/plug/{uuid}', name: 'app_create_plug')]
    public function create(Request $request,ManagerRegistry $doctrine, string $uuid): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $plug = new Plug();
        $form = $this->createForm(CreatePlugType::class, $plug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $station = $entityManager->getRepository(Station::class)->find($uuid);
            $plug->setStation($station);
            $entityManager->persist($plug);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_edit_station',['uuid' => $station->getUuid(),]);
        }

        return $this->renderForm('create_plug/index.html.twig', [
            'controller_name' => 'Plug',
            'form' => $form,
        ]);
    }
}
