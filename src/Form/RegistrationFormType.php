<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('pseudo', TextType::class, [
            'label' => 'Pseudo : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('mail', EmailType::class, [
            'label' => 'Email : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('nom', TextType::class, [
            'label' => 'Nom : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('prenom', TextType::class, [
            'label' => 'Prénom : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('telephone', TelType::class, [
            'label' => 'Téléphone : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('campus', EntityType::class, [
            'label' => 'Campus : ',
            'required' => true,
            'class' => Campus::class,
            'query_builder' => function (CampusRepository $cr) {
                return $cr->createQueryBuilder('campus')->orderBy('campus.nom', 'ASC');
            },
            'choice_label' => 'nom',
        ]);

        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe ne correspondent pas.',
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe : '],
            'second_options' => ['label' => 'Confirmez le mot de passe : '],
        ]);

        $builder->add('photo', FileType::class, [
            'label' => 'Photo de profil : ',
            'required' => false,
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Valider',
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
