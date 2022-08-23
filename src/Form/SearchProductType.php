<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Service\SearchProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SearchProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('categories', EntityType::class,[
            'class' => Category::class,
            'label' => false,
            'required' => false,
            'multiple'=> true,
            'attr' =>[
                'class' => 'js-categories-multiple'
            ]
        ])
        ->add('minprice', IntegerType::class,[
            'required' => false,
            'label' => false,
            'attr' => [
                'placeholder' => 'min ...'
            ]
        ])
        ->add('maxprice', IntegerType::class,[
            'required' => false,
            'label' => false,
            'attr' => [
                'placeholder' => 'max ...'
            ]
        ])
        ->add('tag', TextType::class, [
            'label' => false,
            'required'=> false,
            'attr' => [
                'placeholder' => 'tag ...'
            ]
        ])
        ->add('string',TextType::class,[
            'label' => 'Rechercher',
            'required'=>false,
            'attr' => [
            'placeholder' => 'Votre recherche...',
            ]
        ])
        ->add('submit',SubmitType::class,[
            'label' => "Filtrer",
            'attr' => 
            ['class' =>  'btn-block  btn-info']
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchProduct::class,
            'method' => 'GET',
            'crsf_protection' => false,

        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
