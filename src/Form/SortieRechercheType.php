<?php

namespace App\Form;

use App\Entity\Campus;
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
            ->setMethod('get')
            //ajout ComboBox
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'choice_label' => 'nom',
                'required' => false,
                'mapped'=> false,
            ])
            //ajout TextBox
            ->add('motclef', SearchType::class, [
                'label' => 'Mot-clefs : ',
                'required' => false,
                'mapped'=> false,
            ])
            //ajout DatePicker pour date de début
            ->add('dateDebut', DateType::class, [
                'label' => 'Entre le',
                'html5' => false,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control datetimepicker-input',
                    'data-toggle'=>'datetimepicker',
                    ],
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'mapped'=> false,
            ])
            //ajout DatePicker pour date de fin
            ->add('dateFin', DateType::class, [
                'label' => 'Et le',
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'mapped'=> false,
            ])
            //ajout CheckBox pour organisateur
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
                'data' => true, // Default checked
                'mapped'=> false,
            ])
            //ajout CheckBox déjà inscrit
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
                'data' => true, // Default checked
                'mapped'=> false,
            ])
            //ajout CheckBox pour demander inscription
            ->add('pasInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
                'data' => true, // Default checked
                'mapped'=> false,
            ])
            //ajout CheckBox pour sorties passées
            ->add('passees', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
                'mapped'=> false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ])
            ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}