<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\SaveAndAddButtonType;
use App\Common\Form\Type\SaveButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Tbbc\MoneyBundle\Form\Type\MoneyType;

class WeddingCostEstimateCreateFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('name', TextType::class, ['label' => 'name'])
        ->add('description', TextareaType::class, ['label' => 'description', 'required' => false])
        ->add('estimate', MoneyType::class, ['label' => 'estimate-cost'])
        ->add('real', MoneyType::class, ['label' => 'real-cost'])
        ->add(FormConst::$save, SaveButtonType::class)
        ->add(FormConst::$saveAndAdd, SaveAndAddButtonType::class);
  }
}