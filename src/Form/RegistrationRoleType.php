<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom","Votre prénom ..."))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom","Votre nom de famille ..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre adresse email"))
            ->add('picture', UrlType::class,$this->getConfiguration("Photo de profil","URL de votre avatar ..."))
            ->add('hash', PasswordType::class,$this->getConfiguration("Mot de passe","Choisissez un mot de passe ..."))
            ->add('passwordConfirm', PasswordType::class,$this->getConfiguration("Confirmation de votre mot de passe","Veuillez confirmer votre mot de passe"))
            ->add('userRoles',EntityType::class,[
                'class' => Role::class,
                'choice_label' => 'title',
                'placeholder' => 'choisir son rôle(ROLE_USER par défaut)'
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
