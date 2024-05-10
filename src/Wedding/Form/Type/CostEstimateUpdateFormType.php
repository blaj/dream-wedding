<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\SaveAndAddButtonType;
use App\Common\Form\Type\SaveButtonType;
use App\Wedding\Entity\Enum\UnitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tbbc\MoneyBundle\Form\Type\MoneyType;

class CostEstimateUpdateFormType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['weddingId', 'userId']);
    $resolver->setAllowedTypes('weddingId', 'int');
    $resolver->setAllowedTypes('userId', 'int');
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('name', TextType::class, ['label' => 'name'])
        ->add('description', TextareaType::class, ['label' => 'description', 'required' => false])
        ->add('estimate', MoneyType::class, ['label' => 'estimate-cost'])
        ->add('real', MoneyType::class, ['label' => 'real-cost'])
        ->add('quantity', IntegerType::class, ['label' => 'quantity'])
        ->add('unitType', EnumType::class, ['class' => UnitType::class, 'label' => 'unit-type'])
        ->add(
            'dependsOnGuests',
            CheckboxType::class,
            ['label' => 'depends-on-guests', 'required' => false])
        ->add(
            'group',
            CostEstimateGroupChoiceFormType::class,
            [
                'label' => 'group',
                'required' => false,
                'weddingId' => $options['weddingId'],
                'userId' => $options['userId']])
        ->add(FormConst::$save, SaveButtonType::class);
  }
}