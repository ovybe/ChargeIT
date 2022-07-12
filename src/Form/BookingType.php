<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\Plug;
use App\Entity\UsersCarsREDUNDANT;
use DateInterval;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $cars=$user->getCars();
        $cararray=array();
        $plugsrepo=$this->entityManager->getRepository(Plug::class);
        $plugs=$plugsrepo->findBy(['station'=>$options['stationid'],'status'=>1]);
        $availableplugs=array();
        foreach($plugs as $p){
            $availableplugs[$p->getId().". ".$p->getType()]=$p;
        }

        foreach($cars as $c){
            $plate=$c->getPlate();
            $cararray[$plate]=$c;
        }
//        $timecalc=60*($cararray[array_key_first($cararray)]->getCapacity()/$availableplugs[array_key_first($availableplugs)]->getMax_Output());
//        $duration=ceil($timecalc-(0.3)*$timecalc);
        $current_date=new DateTime('now');
        $builder
            ->add('start_time',DateTimeType::class,[
                'years'=>[date('Y'),date('Y')+1,date('Y')+2],
                'widget'=>'single_text',
                'attr'=>
                    [
                        'value'=>$current_date->format('Y-m-d\TH:i'),
                    'min'=>$current_date->format('Y-m-d\TH:i'),
                        'max'=>$current_date->add(new DateInterval('P' . 1 . 'Y'))->format('Y-m-d\TH:i'),
                    ]
            ])
            ->add('duration',NumberType::class,[
                'attr'=>[
                    'min' => 1,
                ]
            ])
            ->add('car',ChoiceType::class,[
                'choices'=>[
                    'Your cars'=> $cararray,
                ]
            ])
            ->add('plug',ChoiceType::class,[
                'choices'=>$availableplugs
            ])
            ->add('battery', TextType::class,['data'=>'30','mapped'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'stationid' => 0,
        ]);
        $resolver->setAllowedTypes('stationid','string');
    }
}
