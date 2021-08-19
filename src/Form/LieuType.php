<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomLieu', TextType::class, [
                'label' => 'Nom du lieu : ',
                'trim' => true,
                'required' => true,
            ])
            ->add('rue', TextType::class, [
                'label' => 'Rue : ',
                'trim' => true,
                'required' => true,
            ])

            ->add('latitude', NumberType::class, [
                'label' => 'Latitude : ',
                'trim' => true,
                'required' => false,
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude : ',
                'trim' => true,
                'required' => false,
            ])
            ->add('ville', EntityType::class, [
                'label' => 'Ville : ',
                'required' => true,
                'class' => Ville::class,
                'query_builder' => function (VilleRepository $cr) {
                    return $cr->createQueryBuilder('ville')->orderBy('ville.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ]);
//            ->add('sorties', CollectionType::class, [
//                       'entry_type' => SortieType::class,
//                       'allow_add' => true,
//                       'by_reference' => false,
//                       'entry_options' => [
//                           'embedded' => true,
//                       ],
//                       'label' => false,
//                   ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Créer un lieu',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
