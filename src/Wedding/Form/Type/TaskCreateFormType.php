<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\SaveAndAddButtonType;
use App\Common\Form\Type\SaveButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskCreateFormType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['weddingId', 'userId']);
    $resolver->setAllowedTypes('weddingId', 'int');
    $resolver->setAllowedTypes('userId', 'int');
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('name', TextType::class, ['label' => 'name'])
        ->add('description', TextareaType::class, ['label' => 'description', 'required' => false])
        ->add('onDate', DateTimeType::class, ['label' => 'on-date', 'required' => false])
        ->add('setColor', CheckboxType::class, ['label' => 'set-color', 'required' => false])
        ->add('color', ColorType::class, ['label' => 'color', 'required' => false])
        ->add('completed', CheckboxType::class, ['label' => 'completed', 'required' => false])
        ->add('group', TaskGroupChoiceFormType::class,
            [
                'label' => 'group',
                'required' => false,
                'weddingId' => $options['weddingId'],
                'userId' => $options['userId']])
        ->add(FormConst::$save, SaveButtonType::class)
        ->add(FormConst::$saveAndAdd, SaveAndAddButtonType::class);
  }
}