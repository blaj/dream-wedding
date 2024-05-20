<?php

namespace App\Common\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterButtonType extends AbstractType implements SubmitButtonTypeInterface {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(['label' => 'filter']);
  }

  public function getParent(): string {
    return SubmitType::class;
  }
}