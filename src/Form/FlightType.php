<?php

namespace App\Form;

use App\Entity\Flight;
use App\Entity\Airport;
use App\Form\AirportType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class FlightType extends ApplicationType
{ 
    private $test;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $builder->addEventListener(FormEvents::POST_SET_DATA, function ($event) {
        //     $this->test = $event->getData(); // The Form Object
        //     // Do whatever you want here!
        //     return $this->test;
        // });
        $builder
            ->add('numFlight', TextType::class,$this->getConfiguration("Numero vol","Saississez un numéro de vol"))
            ->add('hourDeparture', DateTimeType::class,$this->getConfiguration("Date de départ","Saississez une date de départ", ["widget" => "single_text","format" => "d/M/y H:m:s"]))
            ->add('hourArrival', DateTimeType::class,$this->getConfiguration("Date d'arrivée","Saississez une date d'arrivée", ["widget" => "single_text","format" => "d/M/y H:m:s"]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Flight::class,
        ]);
    }
}
