<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\UsersCars;
use App\Form\BookingType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingManagementController extends AbstractController
{
    #[Route('/booking/management', name: 'app_booking_management')]
    public function index(Request $request,ManagerRegistry $doctrine): Response
    {
        $user=$this->getUser();
        $ip=$request->getClientIp();

        $entityManager= $doctrine->getManager();
        $usercars=$entityManager->getRepository(UsersCars::class)->findBy(['user'=>$user->getId()]);
        $carsrepo=$entityManager->getRepository(Car::class);
        $cars=array();
        foreach($usercars as $u){
        $cars[]= $carsrepo->findOneBy(['plate'=>$u->getCarId()]);
        }
        $bookings=array();
        foreach($cars as $c){
        $bookings[]=$entityManager->getRepository(Booking::class)->findBy(['car'=>$c->getPlate()]);
        }
        return $this->render('booking_management/index.html.twig', [
            'controller_name' => 'BookingManagementController',
            'booking'=>$bookings,
        ]);
    }
    #[Route('/booking/create', name: 'app_booking_create')]
    public function create(Request $request,ManagerRegistry $doctrine): Response
    {
        $booking = new Booking();
        $entityManager = $doctrine->getManager();

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('app_booking_management');
        }

        return $this->renderForm('booking_form/index.html.twig', [
            'form' => $form,
        ]);
    }
}
