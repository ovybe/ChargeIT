<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AddCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plate',TextType::class,['constraints' =>
                [
                new NotBlank(),
                    new Regex('/^[a-zA-Z][a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$|^[a-zA-Z] \d\d [a-zA-Z][a-zA-Z][a-zA-Z]$/  '),
                ]
            ],)
            ->add('plug_type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
