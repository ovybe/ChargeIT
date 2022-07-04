<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\UsersCars;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class BookingType extends AbstractType
{
    private $security;
    private $entityManager;

    public function __construct(Security $security,ManagerRegistry $doctrine)
    {
        $this->security = $security;
        $this->entityManager = $doctrine->getManager();
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user=$this->security->getUser();
        $userscars=$this->entityManager->getRepository(UsersCars::class)->findBy(['user'=>$user->getId()]);
        $cars=array();
        $carsrepo=$this->entityManager->getRepository(Car::class);
        foreach($userscars as $uc){
            $cars[]=$carsrepo->findOneBy(['plate'=>$uc->getCarId()]);
        }
        $cararray=array();
        foreach($cars as $c){
            $plate=$c->getPlate();
            $cararray[$plate]=$plate;
        }
        // REMINDER: LOOK INTO GEOLOCATION (geocoder)
        $builder
            ->add('start_time')
            ->add('duration')
            ->add('car',ChoiceType::class,[
                'choices'=>[
                    'Your cars'=> $cararray,
                ]
            ])
//            ->add('plug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
