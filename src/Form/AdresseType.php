<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
        
        
            ->add('titre', options:[
                'label' => 'Titre'
            ])
            ->add('adrnom', options:[
                'label' => 'Nom'
            ])
            ->add('adrprenom', options:[
                'label' => 'Prénom'
            ])
            ->add('adresse', options:[
                'label' => 'Adresse'
            ])
            ->add('adrcodepostal', options:[
                'label' => 'Code postal'
            ])
            ->add('adrville', options:[
                'label' => 'Ville'
            ])
            ->add('adrtelephone', options:[
                'label' => 'Téléphone'
            ])
            ->add('adrpays', options:[
                'label' => 'Pays'
            ])
            ->add('users', EntityType::class, [
                'class' => Users::class,
                'choice_label' =>'Nom'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
