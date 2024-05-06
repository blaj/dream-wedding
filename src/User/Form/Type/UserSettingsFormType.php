<?php

namespace App\User\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\SaveButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;

class UserSettingsFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('currentPassword', PasswordType::class, ['label' => 'current-password'])
        ->add('newPassword',)
        ->add(
            'newPassword',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'new-password'],
                'second_options' => ['label' => 're-new-password']])
        ->add(FormConst::$save, SaveButtonType::class);
  }
}