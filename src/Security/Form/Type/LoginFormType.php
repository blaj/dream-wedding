<?php

namespace App\Security\Form\Type;

use App\Common\Const\FormConst;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginFormType extends AbstractType {

  public function __construct(private readonly UrlGeneratorInterface $urlGenerator) {}

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->add('username', TextType::class, [
            'data' => $options['lastUsername'],
            'mapped' => false,
            'attr' => ['autocomplete' => 'username'],
            'label' => 'username'])
        ->add('password', PasswordType::class, [
            'mapped' => false,
            'attr' => ['autocomplete' => 'current-password'],
            'label' => 'password'])
        ->add('rememberMe', CheckboxType::class, [
            'mapped' => false,
            'required' => false,
            'label' => 'remember-me'])
        ->add(
            'targetPath',
            HiddenType::class,
            ['data' => $this->urlGenerator->generate('dashboard_index')])
        ->add(FormConst::$captcha, Recaptcha3Type::class, [
            'constraints' => new Recaptcha3(),
            'action_name' => 'homepage']);
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['lastUsername']);
    $resolver->setAllowedTypes('lastUsername', 'string');
    $resolver->setDefaults(['csrf_token_id' => 'authenticate']);
  }
}