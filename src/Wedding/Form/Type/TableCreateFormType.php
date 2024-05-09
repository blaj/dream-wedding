<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
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

class TableCreateFormType extends AbstractType {

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
        ->add('type', EnumType::class, ['class' => TableType::class, 'label' => 'table-type'])
        ->add('numberOfSeats', IntegerType::class, ['label' => 'number-of-seats'])
        ->add(
            'guests',
            GuestChoiceFormType::class,
            [
                'label' => 'guests',
                'required' => false,
                'multiple' => true,
                'weddingId' => $options['weddingId'],
                'userId' => $options['userId']])
        ->add(FormConst::$save, SaveButtonType::class)
        ->add(FormConst::$saveAndAdd, SaveAndAddButtonType::class);
  }
}