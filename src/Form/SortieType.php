<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
             'label' => 'Nom de la sortie : ',
            'trim' => true,
            'required' => true,
            ])
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('infosSortie')
            ->add('motifAnnulation')
            ->add('nbParticipantsMax')
            ->add('etat')
            ->add('campus')
            ->add('participants')
            ->add('organisateur')
            ->add('lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
