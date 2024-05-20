<?php

namespace App\Wedding\Form\Type;

use App\Wedding\Dto\GuestCreateManyRowRequest;
use App\Wedding\Entity\Enum\GuestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestCreateManyRowFormType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefault('data_class', GuestCreateManyRowRequest::class);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->add('firstName', TextType::class, ['label' => 'first-name'])
        ->add('lastName', TextType::class, ['label' => 'last-name'])
        ->add('type', EnumType::class, ['class' => GuestType::class, 'label' => 'guest-type']);
  }
}