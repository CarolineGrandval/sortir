<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AjoutFichierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //ajout chemion de fichier
            ->add('upload_file', FileType::class, [
                'label' => 'Fichier à télécharger',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [ // We want to let upload only txt, csv or Excel files
                            'text/x-comma-separated-values',
                            'text/comma-separated-values',
                            'text/x-csv',
                            'text/csv', //CSV
                            'text/plain',
                            'application/vnd.ms-excel',
                            'application/x-csv',
                            'application/csv',
                            'application/excel',
                        ],
                        'mimeTypesMessage' => "Ce document n'est pas valide.",
                    ])
                ],
                ])

            //ajout Bouton Rechercher
            ->add('submit', SubmitType::class, [
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}