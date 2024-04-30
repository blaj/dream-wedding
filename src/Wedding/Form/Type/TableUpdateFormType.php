<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\SaveAndAddButtonType;
use App\Common\Form\Type\SaveButtonType;
use App\Wedding\Entity\Enum\TableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TableUpdateFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('name', TextType::class, ['label' => 'name'])
        ->add('description', TextareaType::class, ['label' => 'description', 'required' => false])
        ->add('type', EnumType::class, ['class' => TableType::class, 'label' => 'table-type'])
        ->add(FormConst::$save, SaveButtonType::class);
  }
}