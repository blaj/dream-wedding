<?php

namespace App\Wedding\Form\Type;

use App\Common\Const\FormConst;
use App\Common\Form\Type\SaveButtonType;
use App\Wedding\Entity\Enum\RoleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class WeddingUserUpdateFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('PUT')
        ->add('username', TextType::class, ['label' => 'username', 'disabled' => true])
        ->add('userEmail', EmailType::class, ['label' => 'email', 'disabled' => true])
        ->add('role', EnumType::class, ['class' => RoleType::class, 'label' => 'role'])
        ->add(FormConst::$save, SaveButtonType::class);
  }
}