<?php

namespace App\Common\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CKEditorType extends AbstractType {

  public function __construct(private readonly RequestStack $requestStack) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(
        [
            'attr' => [
                'data-controller' => 'ckeditor',
                'data-ckeditor-locale-value' => $this->requestStack->getCurrentRequest()
                    ?->getLocale()]]);
  }

  public function getParent(): string {
    return TextareaType::class;
  }
}