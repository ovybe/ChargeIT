<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Users;
use App\Entity\UsersCarsREDUNDANT;
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
        if ($carcheck!=null) {
            // check if the user already has this plate in his car list
            foreach($user->getCars() as $uc){
                if($uc->getPlate()==$carcheck->getPlate())
                    return 1;
            }
            //dd($user->getId());
            $carcheck->addUser($user);
            $user->addCar($carcheck);

            $entityManager->flush();
        } else {
            //dd($car);
            $car->setUsers(array($user));
            $user->addCar($car);
            $entityManager->persist($car);
            $entityManager->flush();
        }
        return 0;
    }
//    public function CarVerifyEdit(ObjectManager $entityManager,Car $car,Users $user){
//        $carcheck = $entityManager->getRepository(Car::class)->findOneBy(['plate' => $car->getPlate()]);
//        //dd($carcheck);
//        if ($carcheck != null) {
//            $entityManager->flush();
//        } else {
//            //dd($car);
//            //fails
//            return 1;
//        }
//        return 0;
//    }
    public function CarVerifyDelete(ObjectManager $entityManager,Car $car,Users $user){
        if ($car->getBooking() == null) {
            $multipleusercheck = $car->getUsers();
            $multipleusercheck->removeElement($user);
            $user->getCars()->removeElement($car);
            $entityManager->flush();
            if(count($multipleusercheck)==0){
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();

        $car = new Car();
        $form = $this->createForm(AddCarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // check regex
            // ^[a-zA-Z][a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$|^[a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$
            $this->CarVerifyCreate($entityManager,$car,$user);
            return $this->redirectToRoute('app_car_management');
        }

        return $this->renderForm('create_car/index.html.twig', [
            'controller_name' => 'CarManagementController',
            'form' => $form,
        ]);
    }
    #[Route('/edit/car/{plate}', name: 'app_edit_car')]
    public function edit(Request $request,ManagerRegistry $doctrine,string $plate): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();

        $cars=$user->getCars();
        $ok=0;
        foreach($cars as $c) {
            if ($c->getPlate()==$plate) {
                $car = $c;
                $ok=1;
            }
        }
        if($ok==0){
            throw $this->createNotFoundException(
                'No car found for plate '.$plate
            );
        }

        $form = $this->createForm(AddCarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // check regex
            // ^[a-zA-Z][a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$|^[a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$
            $entityManager->flush();
            return $this->redirectToRoute('app_chargeit_main_page_user');
        }

        return $this->renderForm('create_car/index.html.twig', [
            'controller_name' => 'CarManagementController',
            'form' => $form,
        ]);
    }
    #[Route('/delete/car/{plate}', name: 'app_delete_car')]
    public function delete(Request $request,ManagerRegistry $doctrine,string $plate): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();
        $cars=$user->getCars();
        $ok=0;
        foreach($cars as $c) {
            if ($c->getPlate() == $plate) {
                $ok=1;
                $car=$c;
                break;
            }
        }
        if($ok==0)
            throw $this->createNotFoundException(
                'No car found for plate '.$plate
            );
        if($error=$this->CarVerifyDelete($entityManager,$car,$user)!=0){
            return $this->redirectToRoute('app_car_management');
        } // fail


        return $this->redirectToRoute('app_car_management');
    }
    #[Route('/car/management', name: 'app_car_management')]
    public function manage(Request $request,ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();
        $usercars=$user->getCars(); // GET CARS FROM USER
        // LAZY INITIALIZATION
        return $this->renderForm('car_management/index.html.twig', [
            'cars' => $usercars,
        ]);
    }
}
