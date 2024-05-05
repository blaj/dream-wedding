<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\SaveAndAddButtonType;
use App\Common\Form\Type\SaveButtonType;
use App\Wedding\Entity\Enum\DietType;
use App\Wedding\Entity\Enum\GuestType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestCreateFormType extends AbstractType {

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['weddingId', 'userId']);
    $resolver->setAllowedTypes('weddingId', 'int');
    $resolver->setAllowedTypes('userId', 'int');
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('firstName', TextType::class, ['label' => 'first-name'])
        ->add('lastName', TextType::class, ['label' => 'last-name'])
        ->add('type', EnumType::class, ['class' => GuestType::class, 'label' => 'guest-type'])
        ->add('invited', CheckboxType::class, ['label' => 'invited', 'required' => false])
        ->add('confirmed', CheckboxType::class, ['label' => 'confirmed', 'required' => false])
        ->add(
            'accommodation',
            CheckboxType::class,
            ['label' => 'accommodation', 'required' => false])
        ->add('transport', CheckboxType::class, ['label' => 'transport', 'required' => false])
        ->add('dietType', EnumType::class, ['class' => DietType::class, 'label' => 'diet-type'])
        ->add('note', TextareaType::class, ['label' => 'note', 'required' => false])
        ->add('telephone', TextType::class, ['label' => 'telephone', 'required' => false])
        ->add('email', EmailType::class, ['label' => 'email', 'required' => false])
        ->add('payment', IntegerType::class, ['label' => 'payment'])
        ->add(
            'groups',
            GuestGroupChoiceFormType::class,
            [
                'label' => 'guest-groups',
                'multiple' => true,
                'required' => false,
                'weddingId' => $options['weddingId'],
                'userId' => $options['userId']])
        ->add('table', TableChoiceFormType::class, [
            'label' => 'table',
            'required' => false,
            'weddingId' => $options['weddingId'],
            'userId' => $options['userId']])
        ->add(FormConst::$save, SaveButtonType::class)
        ->add(FormConst::$saveAndAdd, SaveAndAddButtonType::class);
  }
}