<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\UserRepository;
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
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de début : ',
                'required' => true,
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée : ',
                'required' => true,
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date et heure de fin d\'inscription : ',
                'required' => true,
            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos : ',
                'required' => true,
            ])
            ->add('motifAnnulation', TextareaType::class, [
                'label' => 'Motif d\'annulation : ',
                'required' => false,
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
            ])

            ->add('lieu', EntityType::class, [
                'label' => 'Lieu : ',
                'required' => true,
                'class' => Lieu::class,
                'query_builder' => function (LieuRepository $cr) {
                    return $cr->createQueryBuilder('lieu')->orderBy('lieu.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ])
        ;

        //TODO Ajouter ville, rue code postal, longitude, latitude (voir maquette)

        $builder->add('submit', SubmitType::class, [
            'label' => 'Créer la sortie',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
