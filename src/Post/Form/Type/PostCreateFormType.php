<?php

namespace App\Post\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\CKEditorType;
use App\Common\Form\Type\SaveAndAddButtonType;
use App\Common\Form\Type\SaveButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostCreateFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('title', TextType::class, ['label' => 'title'])
        ->add('content', CKEditorType::class, ['label' => 'content', 'required' => false])
        ->add('headingImage', FileType::class, ['label' => 'heading-image', 'required' => false])
        ->add('shortContent', TextareaType::class, ['label' => 'short-content'])
        ->add(
            'categories',
            PostCategoryChoiceFormType::class,
            [
                'label' => 'categories',
                'multiple' => true,
                'required' => false,
                'attr' => ['data-controller' => 'tom-select']])
        ->add(
            'tags',
            PostTagChoiceFormType::class,
            [
                'label' => 'tags',
                'multiple' => true,
                'required' => false,
                'attr' => ['data-controller' => 'tom-select']])
        ->add(FormConst::$save, SaveButtonType::class)
        ->add(FormConst::$saveAndAdd, SaveAndAddButtonType::class);
  }
}