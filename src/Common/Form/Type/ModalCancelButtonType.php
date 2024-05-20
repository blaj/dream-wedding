<?php

namespace App\Common\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ButtonTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModalCancelButtonType extends AbstractType implements ButtonTypeInterface {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(['label' => 'cancel', 'attr' => ['data-action' => 'modal#closeModal']]);
  }

  public function getParent(): string {
    return ButtonType::class;
  }
}