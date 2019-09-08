<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class,$this->getConfiguration("Prénom",""))
            ->add('lastName',TextType::class,$this->getConfiguration("Nom",""))
            ->add('email',EmailType::class,$this->getConfiguration("Adresse mail",""))
            ->add('picture',TextType::class,$this->getConfiguration("URL avatar","insérer l'url de votre avatar"))
            ->add('userRoles',EntityType::class,[
                'class' => Role::class,
                'choices' => $group->getUserRoles(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
