<?php

namespace App\Controller;

use App\Entity\Plug;
use App\Entity\Station;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChargeITMainPageController extends AbstractController
{
    // MAIN PAGE
    #[Route('/', name: 'app_chargeit_main_page')]
    public function index(ManagerRegistry $doctrine): Response
    {
        // Fetch stations and plugs
        $station = $doctrine->getRepository(Station::class)->findAll();
        $plug = $doctrine->getRepository(Plug::class)->findAll();
        //Build the array containing the plugs based on each station
        $output = array();
        foreach($plug as $p){
            $key=$p->getStation();
            if(!array_key_exists($key,$output))
                $output[$key]=array($p);
            else
                $output[$key][] = $p;
        }
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
            'plug' => $output,
        ]);
    }
}
