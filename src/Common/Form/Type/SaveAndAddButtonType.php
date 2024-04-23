<?php

namespace App\Common\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButtonTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaveAndAddButtonType extends AbstractType implements SubmitButtonTypeInterface {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(['label' => 'save-and-add']);
  }

  public function getParent(): string {
    return SubmitType::class;
  }
}