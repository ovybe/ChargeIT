<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\UsersCarsREDUNDANT;
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

        $entityManager= $doctrine->getManager();

        $cars=$user->getCars();

        $bookings=array();
        foreach($cars as $c){
//            $book=$c->getCarBooking();
            if(($book=$c->getBooking())!=null)
                $bookings[]=$book;
        }
//        dd($bookings);
        return $this->render('booking_management/index.html.twig', [
            'controller_name' => 'BookingManagementController',
            'booking'=>$bookings,
        ]);
    }
    #[Route('/booking/create/{uuid}', name: 'app_booking_create')]
    public function create(Request $request,ManagerRegistry $doctrine,string $uuid): Response
    {
        $booking = new Booking();
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(BookingType::class,$booking, options: ['stationid'=>$uuid]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkbooking=$booking->getCar()->getBooking();
            if($checkbooking!=null)
                {
//                    dd($checkbooking);
                    //error occured
                    $error="There already is a booking for the car plate '".$checkbooking->getBookingcar()->getPlate()."' !";
                    return $this->renderForm('booking_form/index.html.twig', [
                        'form' => $form,
                        'errors' => $error,
                    ]);
                }
            //dd($booking);
            $booking->setCar($booking->getCar());
            $entityManager->persist($booking);
            $entityManager->flush();
            return $this->redirectToRoute('app_booking_management');
        }

        return $this->renderForm('booking_form/index.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/booking/delete/{uuid}', name: 'app_booking_delete')]
    public function delete(Request $request,ManagerRegistry $doctrine, string $uuid): Response
    {
        $entityManager = $doctrine->getManager();
        $bookingsrepo= $entityManager->getRepository(Booking::class);
        $booking = $bookingsrepo->findOneBy(['id'=>$uuid]);

        if (!$booking) {
            return $this->redirectToRoute('app_booking_management');
        }
        $booking->setCar(null);
        $entityManager->remove($booking);
        $entityManager->flush();

        return $this->redirectToRoute('app_booking_management');
    }
}
