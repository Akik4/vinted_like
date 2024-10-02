<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('fav', IntegerType::class)
            ->add('content', TextareaType::class, ['required'=>false])   
            ->add('favoris', SubmitType::class, ['label' => 'favoris'])
            ->add('buy', SubmitType::class, ['label' => 'Buy'])
            ->add('save', SubmitType::class, ['label' => 'Mettre en vente'])    
            ->add('delete', SubmitType::class, ['label' => 'supprimer'])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
