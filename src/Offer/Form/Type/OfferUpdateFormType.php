<?php

namespace App\Offer\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\CKEditorType;
use App\Common\Form\Type\SaveAndAddButtonType;
use App\Common\Form\Type\SaveButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OfferUpdateFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('title', TextType::class, ['label' => 'title'])
        ->add('content', CKEditorType::class, ['label' => 'content', 'required' => false])
        ->add(
            'categories',
            OfferCategoryChoiceFormType::class,
            [
                'label' => 'categories',
                'multiple' => true,
                'required' => false,
                'attr' => ['data-controller' => 'tom-select']])
        ->add(FormConst::$save, SaveButtonType::class);
  }
}