<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $listener = function (FormEvent $event) {
            // ...
        };

        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie : ',
                'trim' => true,
                'required' => true,
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de début : ',
                'required' => true,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée : ',
                'required' => true,
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date et heure de fin d\'inscription : ',
                'required' => true,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos : ',
                'required' => true,
            ])
            ->add('nbParticipantsMax', IntegerType::class, [
                'label' => 'Nombre de participants : ',
                'required' => true,
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'required' => true,
                'class' => Campus::class,
                'query_builder' => function (CampusRepository $cr) {
                    return $cr->createQueryBuilder('campus')->orderBy('campus.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'disabled' => true,
            ])

            //TODO : afficher la ville liée au lieu par défaut (en édition).
            ->add('ville', EntityType::class, [
                'label' => 'Ville : ',
                'required' => true,
                'class' => Ville::class,
                'mapped' => false,
                'query_builder' => function (VilleRepository $cr) {
                    return $cr->createQueryBuilder('ville')
                        ->orderBy('ville.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'placeholder' => '',
            ]);

        $builder->add('lieu', EntityType::class, [
            'label' => 'Lieu : ',
            'required' => true,
            'class' => Lieu::class,
            'query_builder' => function (LieuRepository $cr) {
                return $cr->createQueryBuilder('lieu')->orderBy('lieu.nomLieu', 'ASC');
            },
            'choice_label' => 'nomLieu',
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',
        ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'embedded' => false,
            'is_admin' => false,
        ]);
    }
}
