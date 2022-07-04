<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Users;
use App\Entity\UsersCars;
use App\Form\AddCarType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarManagementController extends AbstractController
{
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
            $carcheck= $entityManager->getRepository(Car::class)->findOneBy(['plate'=>$car->getPlate()]);
            $usercars= new UsersCars();
            if($carcheck!=null){
                $usercarscheck = $entityManager->getRepository(UsersCars::class)->findOneBy(['user'=>$user->getId()]);
                //dd($user->getId());
                if($usercarscheck){
                    return $this->redirectToRoute('app_chargeit_main_page');
                }
            $car->addUserId($user);
            $entityManager->persist($car);
            $usercars->setCarId($car)->setUserId($user);

            $entityManager->persist($usercars);
            $entityManager->flush();
//            $entityManager->flush();
            }
            else{
                $usercars->setCarId($carcheck)->setUserId($user);
                $entityManager->persist($usercars);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_chargeit_main_page');
        }

        return $this->renderForm('create_car/index.html.twig', [
            'controller_name' => 'CarManagementController',
            'form' => $form,
        ]);
    }
}
