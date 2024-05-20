<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\ChoiceBoolFormType;
use App\Common\Form\Type\FilterButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GuestListFilterFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('GET')
        ->add('firstName', TextType::class, ['label' => 'first-name', 'required' => false])
        ->add('lastName', TextType::class, ['label' => 'last-name', 'required' => false])
        ->add('invited', ChoiceBoolFormType::class)
        ->add(FormConst::$filter, FilterButtonType::class);
  }
}