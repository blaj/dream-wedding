<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\AddButtonType;
use App\Common\Form\Type\SaveAndAddButtonType;
use App\Common\Form\Type\SaveButtonType;
use App\Wedding\Entity\Enum\TableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableSimpleCreateFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('name', TextType::class, ['label' => 'name'])
        ->add('type', EnumType::class, ['class' => TableType::class, 'label' => 'table-type'])
        ->add('numberOfSeats', IntegerType::class, ['label' => 'number-of-seats'])
        ->add(FormConst::$add, AddButtonType::class);
  }
}