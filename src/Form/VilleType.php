<?php

namespace App\Form;

use App\Entity\Ville;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la ville : ',
                'trim' => true,
                'required' => true,
            ])
            ->add('codePostal', NumberType::class, [
                'label' => 'Code postal : ',
                'trim' => true,
                'required' => true,
            ])
        ;
        $builder->add('submit', SubmitType::class, [
            'label' => 'Créer une ville',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ville::class,
        ]);
    }
}
