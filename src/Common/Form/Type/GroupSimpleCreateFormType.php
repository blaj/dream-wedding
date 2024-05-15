<?php

namespace App\Common\Form\Type;

use App\Common\Const\FormConst;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupSimpleCreateFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('name', TextType::class, ['label' => 'name', 'attr' => ['placeholder' => 'name']])
        ->add(FormConst::$add, AddButtonType::class);
  }
}