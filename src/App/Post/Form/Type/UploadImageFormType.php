<?php

namespace App\App\Post\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class UploadImageFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder->add('file', FileType::class);
  }
}