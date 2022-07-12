<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Plug;
use App\Entity\Station;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChargeITMainPageController extends AbstractController
{
    // MAIN PAGE
    #[Route('/admin/', name: 'app_chargeit_main_page')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();

//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
    #[Route('/fetcher/snp/', name: 'app_fetcher_snp')]
    public function stationAndPlugFetcher(Request $request,ManagerRegistry $doctrine): Response
    {
        //        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

//        $routeParameters = $request->attributes->get('id');

        $entityManager = $doctrine->getManager();
        $stationsrepo = $entityManager->getRepository(Station::class);
        $stations = $stationsrepo->findAll();

        if (!$stations) {
            return new Response('{}',status:404);
        }

//        $plugs=new stdClass();
//        foreach($stations as $s){
//
//        foreach($s->getPlugs() as $plug){
//            $pid=$plug->getId();
//            $plugs->$pid=$plug->getMax_Output();
//        }
//        }
        $jsonData = [];
        foreach($stations as $s){
            $jsonObj = new stdClass();
        $jsonObj->id = $s->getId();
        $jsonObj->uuid = $s->getUuid();
        $jsonObj->name = $s->getName();
        $jsonObj->location = $s->getLocation();
        $jsonObj->latitude = $s->getLatitude();
        $jsonObj->longitude = $s->getLongitude();
        $types=[];
        foreach($s->getPlugs() as $plug){
            $plug_type=$plug->getType();
            if(!in_array($plug_type,$types)){
                $types[]=$plug_type;
            }
        }
        $jsonObj->types = $types;
        // fetch plugs
        $jsonData[]=$jsonObj;
        }
//        $jsonData->plugs = $plugs;
//        $jsonData->stations = json_encode($stations);

        return new Response(json_encode($jsonData));
//        }
//        return new Response();
    }
    #[Route('/fetcher/plugs/', name: 'app_fetcher_plugs')]
    public function plugFetcher(Request $request,ManagerRegistry $doctrine): Response
    {
        //        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {

        $routeParameter = $request->get('id');
//        dd($routeParameters);

        $entityManager = $doctrine->getManager();
        $stationsrepo = $entityManager->getRepository(Station::class);
        $station = $stationsrepo->findOneBy(['uuid'=>$routeParameter]);

        if (!$station) {
            return new Response('{}',status:404);
        }

//        $plugs=new stdClass();
//        foreach($stations as $s){
//
//        foreach($s->getPlugs() as $plug){
//            $pid=$plug->getId();
//            $plugs->$pid=$plug->getMax_Output();
//        }
//        }
        $plugs=$station->getPlugs();
        $jsonData = [];
        foreach($plugs as $p){
            $jsonObj = new stdClass();
            $jsonObj->id = $p->getId();
            $jsonObj->type = $p->getType();
            $jsonObj->status = $p->isStatus();
            $jsonObj->output = $p->getMax_Output();
            // fetch plugs
            $jsonData[]=$jsonObj;
        }
//        $jsonData->plugs = $plugs;
//        $jsonData->stations = json_encode($stations);

        return new JsonResponse($jsonData);
//        }
//        return new Response();
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
