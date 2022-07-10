<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\Station;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use App\Form\BookingType;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
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

        $bookings=new ArrayCollection();
        foreach($cars as $c){
//            $book=$c->getCarBooking();
            if(count($book=$c->getBookings()))
                $bookings->add($book);
//                $bookings[]=$book;
        }
//        dd($bookings);
        return $this->render('booking_management/index.html.twig', [
            'controller_name' => 'BookingManagementController',
            'booking'=>$bookings,
        ]);
    }
    #[Route('/booking/create/{uuid}', name: 'app_booking_create')]
    public function create(Request $request,ManagerRegistry $doctrine,string $uuid) : Response
    {
        $cars= $this->getUser()->getCars();

        $booking = new Booking();
        $entityManager = $doctrine->getManager();
        $station= $entityManager->getRepository(Station::class)->findOneBy(['id'=>$uuid]);

        $form = $this->createForm(BookingType::class,$booking, options: ['stationid'=>$uuid]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $checkbooking=$booking->getCar()->getBookings();
            foreach($checkbooking as $cb){
                if($cb->getStartTime()>new DateTime('now')){
                    $error="There already is a booking for the car plate '".$cb->getCar()->getPlate()."' !";
                    return $this->renderForm('booking_form/index.html.twig', [
                        'form' => $form,
                        'errors' => $error,
                        'uuid' => $uuid,
                    ]);
                }
            }
            //dd($booking);
            $entityManager->persist($booking);
            $entityManager->flush();
            return $this->redirectToRoute('app_booking_management');
        }

        return $this->renderForm('booking_form/index.html.twig', [
            'form' => $form,
            'uuid' => $uuid,

        ]);

    }
    #[Route('/booking/ajax/{id}', name: 'app_booking_ajax')]
    public function ajax(Request $request,ManagerRegistry $doctrine): Response
    {
//        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

            $routeParameters = $request->attributes->get('id');
            $user = $this->getUser();
            $cars = $user->getCars();

            $entityManager = $doctrine->getManager();
            $stationsrepo = $entityManager->getRepository(Station::class);
//            return new Response(json_encode($routeParameters),status:418);
            $station = $stationsrepo->findOneBy(['id' => $routeParameters]);

            if (!$station) {
                return new Response('{}',status:404);
            }
            $caparray=new stdClass();
            foreach($cars as $c){
                $plate=$c->getPlate();
                $caparray->$plate=$c->getCapacity();
            }
            $plugs=new stdClass();
            foreach($station->getPlugs() as $plug){
                $pid=$plug->getId();
                $plugs->$pid=$plug->getMax_Output();
            }
            $jsonData = new stdClass();
            $jsonData->plugs = $plugs;
            $jsonData->capacities = $caparray;

            return new Response(json_encode($jsonData));
//        }
//        return new Response();
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
