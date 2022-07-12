<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\Station;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use App\Form\BookingType;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingManagementController extends AbstractController
{
    public function returnError(Form $form,string $error,string $uuid): Response{
        return $this->renderForm('booking_form/index.html.twig', [
            'form' => $form,
            'errors' => $error,
            'uuid' => $uuid,
        ]);
    }
    #[Route('/booking/management', name: 'app_booking_management')]
    public function index(Request $request,ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user=$this->getUser();

        $entityManager= $doctrine->getManager();

        $cars=$user->getCars();

        $bookings=new ArrayCollection();
        foreach($cars as $c){
//            $book=$c->getCarBooking();
            if(count($book=$c->getBookings()))
            {   $current_date=new DateTime('now');
                foreach($book as $b)
                    if($b->getStartTime()>=$current_date || $b->getStartTime()->add(DateInterval::createFromDateString($b->getDuration().' minutes'))>$current_date)
                        $bookings->add($b);
            }
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $cars= $this->getUser()->getCars();

        $booking = new Booking();
        $entityManager = $doctrine->getManager();
        $station= $entityManager->getRepository(Station::class)->findOneBy(['id'=>$uuid]);

        $form = $this->createForm(BookingType::class,$booking, options: ['stationid'=>$uuid]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $durationcheck=$booking->getDuration();
            if($durationcheck<0 || $durationcheck>10080){
                return $this->returnError($form,"Duration can't be lower than 0 min or higher than 10080 min (a week)",$uuid);
            }

            $checkbooking=$booking->getCar()->getBookings();
//            dd($checkbooking);
            foreach($checkbooking as $cb){
                if($cb->getStartTime()>new DateTime('now') || $cb->getStartTime()->add(new DateInterval('PT' . $cb->getDuration() . 'M'))>new DateTime('now')){
                    return $this->returnError($form,"There already is a booking for the car plate '".$cb->getCar()->getPlate()."' !",$uuid);
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
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
            $carplugsarray=new stdClass();
            foreach($cars as $c){
                $plate=$c->getPlate();
                $caparray->$plate=$c->getCapacity();
                $carplugsarray->$plate=$c->getPlugType();
            }
            $plugs=new stdClass();
            foreach($station->getPlugs() as $plug){
                $pid=$plug->getId();
                $plugs->$pid=$plug->getMax_Output();
            }
            $jsonData = new stdClass();
            $jsonData->plugs = $plugs;
            $jsonData->capacities = $caparray;
            $jsonData->carplugs = $carplugsarray;

            return new Response(json_encode($jsonData));
//        }
//        return new Response();
    }
    #[Route('/booking/delete/{uuid}', name: 'app_booking_delete')]
    public function delete(Request $request,ManagerRegistry $doctrine, string $uuid): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
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
