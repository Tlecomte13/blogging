<?php

namespace App\Form\Article;

use App\Entity\Article\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Image couverture d\'article',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'mb-3'
                ],
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'maxHeight' => '300',
                        'maxWidth' => '300',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Format JPEG ou PNG uniquement',
                    ])
                ]
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr'  => [
                    'class' => 'mb-4'
                ]
            ])
            ->add('content', TextAreaType::class, [
                'label' => 'Contenu de l\'article',
                'attr'  => [
                    'class' => 'mb-4',
                    'rows'  => '6'
                ]
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tags (Appuyer sur entré pour valider le tags)',
                'attr' => [
                    'id' => 'select',
                    'data-role' => 'tagsinput',
                    'class' => 'mb-4',
                    'required' => false
                ]
            ])
            ->add('public', CheckboxType::class, [
                'label' => 'Rendre l`article publique'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
