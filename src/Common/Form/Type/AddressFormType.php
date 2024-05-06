<?php

namespace App\Common\Form\Type;

use App\Common\Dto\AddressRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressFormType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefault('data_class', AddressRequest::class);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->add('city', TextType::class, ['label' => 'city', 'required' => false])
        ->add('street', TextType::class, ['label' => 'street', 'required' => false])
        ->add('postcode', TextType::class, ['label' => 'postcode', 'required' => false]);
  }
}