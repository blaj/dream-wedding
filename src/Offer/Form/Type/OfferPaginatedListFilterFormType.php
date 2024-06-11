<?php

namespace App\Offer\Form\Type;

use App\Offer\Dto\OfferPaginatedListFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferPaginatedListFilterFormType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(['data_class' => OfferPaginatedListFilter::class]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->add('searchBy', TextType::class, ['label' => 'search-by', 'required' => false])
        ->add(
            'categories',
            OfferCategoryChoiceFormType::class,
            ['label' => 'categories', 'required' => false, 'multiple' => true, 'expanded' => true]);
  }
}