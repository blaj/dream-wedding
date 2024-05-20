<?php

namespace App\Common\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceBoolFormType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(['choices' => ['all' => null, 'yes' => true, 'no' => false]]);
  }

  public function getParent(): string {
    return ChoiceType::class;
  }
}