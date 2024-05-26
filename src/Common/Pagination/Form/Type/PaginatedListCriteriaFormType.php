<?php

namespace App\Common\Pagination\Form\Type;

use App\Common\Const\FormConst;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PaginatedListCriteriaFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->setMethod('GET')
        ->add('sort', SortFormType::class)
        ->add('pageCriteria', PageCriteriaFormType::class)
        ->add(FormConst::$filterSubmit, SubmitType::class, ['label' => 'filter']);
  }
}