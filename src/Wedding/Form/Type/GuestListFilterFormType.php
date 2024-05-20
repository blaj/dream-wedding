<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\ChoiceBoolFormType;
use App\Common\Form\Type\FilterButtonType;
use App\Common\Form\Type\ModalCancelButtonType;
use App\Wedding\Entity\Enum\DietType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GuestListFilterFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('GET')
        ->add('firstName', TextType::class, ['label' => 'first-name', 'required' => false])
        ->add('lastName', TextType::class, ['label' => 'last-name', 'required' => false])
        ->add('invited', ChoiceBoolFormType::class, ['label' => 'invited', 'required' => false])
        ->add('confirmed', ChoiceBoolFormType::class, ['label' => 'confirmed', 'required' => false])
        ->add(
            'accommodation',
            ChoiceBoolFormType::class,
            ['label' => 'accommodation', 'required' => false])
        ->add('transport', ChoiceBoolFormType::class, ['label' => 'transport', 'required' => false])
        ->add(
            'dietType',
            EnumType::class,
            ['class' => DietType::class, 'label' => 'diet-type', 'required' => false])
        ->add(FormConst::$filter, FilterButtonType::class)
        ->add(FormConst::$modalCancel, ModalCancelButtonType::class);
  }
}