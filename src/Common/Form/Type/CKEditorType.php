<?php

namespace App\Common\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class CKEditorType extends AbstractType {

  public function __construct(private readonly RouterInterface $router) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(
        [
            'attr' => [
                'data-controller' => 'ckeditor',
                'data-ckeditor-upload-url-value' => $this->router->generate(
                    'app_post_upload_image')]]);
  }

  public function getParent(): string {
    return TextareaType::class;
  }
}