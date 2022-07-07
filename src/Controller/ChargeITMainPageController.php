<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Plug;
use App\Entity\Station;
use App\Entity\UsersCarsREDUNDANT;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChargeITMainPageController extends AbstractController
{
    // MAIN PAGE
    #[Route('/admin/', name: 'app_chargeit_main_page')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // Fetch stations, plugs, cars
        $station = $doctrine->getRepository(Station::class)->findAll();
        //Build the array containing the plugs based on each station
//        $output=$this->build_plug_array($plug,$station);
        // FOR DEBUGGING
//        foreach($output as $o){
//            print_r($o);
//            echo '<br>';
//        }
        if (!$station) {
            throw $this->createNotFoundException(
                'No station found'
            );
        }
        // FOR DEBUGGING
        //return new Response('Done.');
        return $this->render('charge_it_main_page/index.html.twig', [
            'controller_name' => 'ChargeITMainPageController',
            'station' => $station,
//            'plug' => $output,
            'name' => $this->getUser()->getName(),
        ]);
    }
    #[Route('/', name: 'app_chargeit_main_page_user')]
    public function userPage(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // Fetch stations, plugs, cars
        $station = $doctrine->getRepository(Station::class)->findAll();
        $cars = $user->getCars();
        //Build the array containing the plugs based on each station
//        $output=$this->build_plug_array($plug,$station);
        // FOR DEBUGGING
//        foreach($output as $o){
//            print_r($o);
//            echo '<br>';
//        }
        if (!$station) {
            throw $this->createNotFoundException(
                'No station found'
            );
        }
        // FOR DEBUGGING
        //return new Response('Done.');
        return $this->render('charge_it_main_page_user/index.html.twig', [
            'controller_name' => 'ChargeITMainPageController',
            'station' => $station,
//            'plug' => $output,
            'name' => $this->getUser()->getName(),
            'cars' => $cars,
        ]);
    }
    public function build_plug_array(array $plug,array $station): ?array{
        $output = array();
        foreach($plug as $p){
            $key=$p->getStation();
            if(!array_key_exists($key,$output))
                $output[$key]=array($p);
            else
                $output[$key][] = $p;
        }
        return $output;
    }
}
