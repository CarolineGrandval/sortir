<?php

namespace App\Form;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SortieRechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //ajout ComboBox
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'required' => true,
                'class' => Campus::class,
                'query_builder' => function (CampusRepository $cr) {
                    return $cr->createQueryBuilder('campus')->orderBy('campus.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'disabled' => false,
            ])

            //ajout TextBox
            ->add('motclef', SearchType::class, [
                'label' => 'Mot-clefs : ',
                'required' => false,
            ])

            //ajout DatePicker pour date de début
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre le',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required' => false,
            ])

            //ajout DatePicker pour date de fin
            ->add('dateFin', DateType::class, [
                'label' => 'Et le',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required' => false,
            ])

            //ajout CheckBox pour organisateur
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])

            //ajout CheckBox déjà inscrit
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])

            //ajout CheckBox pour demander inscription
            ->add('pasInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])

            //ajout CheckBox pour sorties passées
            ->add('passees', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])

            //ajout Bouton Rechercher
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true
        ]);
    }
}