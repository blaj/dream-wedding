<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\ModalCancelButtonType;
use App\Common\Form\Type\SaveButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class GuestCreateManyFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add(
            'rows',
            CollectionType::class,
            [
                'label' => '',
                'entry_type' => GuestCreateManyRowFormType::class,
                'allow_add' => true,
                'allow_delete' => true])
        ->add(FormConst::$save, SaveButtonType::class)
        ->add(FormConst::$modalCancel, ModalCancelButtonType::class);
  }
}