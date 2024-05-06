<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\AddressFormType;
use App\Common\Form\Type\SaveButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Tbbc\MoneyBundle\Form\Type\MoneyType;

class WeddingUpdateFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('name', TextType::class, ['label' => 'name'])
        ->add('onDate', DateType::class, ['label' => 'on-date'])
        ->add('budget', MoneyType::class, ['label' => 'budget'])
        ->add('weddingAddress', AddressFormType::class, ['label' => 'wedding-address'])
        ->add('partyAddress', AddressFormType::class, ['label' => 'party-address'])
        ->add(FormConst::$save, SaveButtonType::class);
  }
}