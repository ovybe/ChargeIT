<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Users;
use App\Entity\UsersCars;
use App\Form\AddCarType;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarManagementController extends AbstractController
{
    public function CarVerifyCreate(ObjectManager $entityManager,Car $car,Users $user){
        $carcheck = $entityManager->getRepository(Car::class)->findOneBy(['plate' => $car->getPlate()]);
        $usercars = new UsersCars();
        if ($carcheck!=null) {
            $usercarscheck = $entityManager->getRepository(UsersCars::class)->findOneBy(['user' => $user->getId(),'car'=>$car->getPlate()]);
            //dd($user->getId());
            if ($usercarscheck!=null) {
                //dd($usercarscheck);
                return 1;
            }
            $usercars->setCarId($carcheck)->setUserId($user);

            $entityManager->persist($usercars);
            $entityManager->flush();
//            $entityManager->flush();
        } else {
            //dd($car);
            $car->addUserId($user);
            $entityManager->persist($car);
            $usercars->setCarId($car)->setUserId($user);
            $entityManager->persist($usercars);
            $entityManager->flush();
        }
        return 0;
    }
    public function CarVerifyEdit(ObjectManager $entityManager,Car $car,Users $user){
        $carcheck = $entityManager->getRepository(Car::class)->findOneBy(['plate' => $car->getPlate()]);
        //dd($carcheck);
        $usercars = new UsersCars();
        if ($carcheck != null) {
            $entityManager->flush();
        } else {
            //dd($car);
            //fails
            return 1;
        }
        return 0;
    }
    public function CarVerifyDelete(ObjectManager $entityManager,Car $car,Users $user){
        $usercarcheck = $entityManager->getRepository(UsersCars::class)->findOneBy(['user'=>$user->getId(),'car'=>$car->getPlate()]);
        //dd($carcheck);
        if ($usercarcheck != null) {
            $multipleusercheck = $entityManager->getRepository(UsersCars::class)->findBy(['car'=>$car->getPlate()]);
            $entityManager->remove($usercarcheck);
            $entityManager->flush();
            if(count($multipleusercheck)==1){
                $entityManager->remove($car);
                $entityManager->flush();
            }

        } else {
            //dd($car);
            //fails
            return 1;
        }
        return 0;
    }
    #[Route('/create/car/', name: 'app_create_car')]
    public function create(Request $request,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();

        $car = new Car();
        $form = $this->createForm(AddCarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // check regex
            // ^[a-zA-Z][a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$|^[a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$
            $this->CarVerifyCreate($entityManager,$car,$user);
            return $this->redirectToRoute('app_chargeit_main_page_user');
        }

        return $this->renderForm('create_car/index.html.twig', [
            'controller_name' => 'CarManagementController',
            'form' => $form,
        ]);
    }
    #[Route('/edit/car/{plate}', name: 'app_edit_car')]
    public function edit(Request $request,ManagerRegistry $doctrine,string $plate): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();

        $carrepo = $entityManager->getRepository(Car::class);
        $car = $carrepo->findOneBy(['plate'=>$plate]);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for plate '.$plate
            );
        }

        $form = $this->createForm(AddCarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // check regex
            // ^[a-zA-Z][a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$|^[a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$
            if($this->CarVerifyEdit($entityManager,$car,$user)==0)
            return $this->redirectToRoute('app_chargeit_main_page_user');
                else
                {//fail
                    return $this->redirectToRoute('app_chargeit_main_page_user');}
        }

        return $this->renderForm('create_car/index.html.twig', [
            'controller_name' => 'CarManagementController',
            'form' => $form,
        ]);
    }
    #[Route('/delete/car/{plate}', name: 'app_delete_car')]
    public function delete(Request $request,ManagerRegistry $doctrine,string $plate): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();

        $carrepo = $entityManager->getRepository(Car::class);
        $car = $carrepo->findOneBy(['plate'=>$plate]);

        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for plate '.$plate
            );
        }

        if($this->CarVerifyDelete($entityManager,$car,$user)!=0)
            return $this->redirectToRoute('app_chargeit_main_page_user'); // fail


        return $this->redirectToRoute('app_chargeit_main_page_user');
    }
    #[Route('/car/management', name: 'app_car_management')]
    public function manage(Request $request,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();

        $carrepo = $entityManager->getRepository(Car::class);
        $userscarsrepo = $entityManager->getRepository(UsersCars::class);
        $userscars = $userscarsrepo->findBy(['user'=>$user->getId()]);
        $car = array();
        foreach($userscars as $uc){
            $car[]=$carrepo->findOneBy(['plate'=>$uc->getCarId()]);
        }

        return $this->renderForm('car_management/index.html.twig', [
            'cars' => $car,
        ]);
    }
}
