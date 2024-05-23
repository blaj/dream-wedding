<?php

namespace App\User\Form\Type;

use App\Common\Const\FormConst;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserRegisterFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('POST')
        ->add('username', TextType::class, ['label' => 'username'])
        ->add('email', EmailType::class, ['label' => 'email'])
        ->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'password'],
                'second_options' => ['label' => 're-password']])
        ->add(FormConst::$captcha, Recaptcha3Type::class, [
            'constraints' => new Recaptcha3(),
            'action_name' => 'homepage'])
        ->add(FormConst::$save, SubmitType::class, ['label' => 'sign-up']);
  }
}