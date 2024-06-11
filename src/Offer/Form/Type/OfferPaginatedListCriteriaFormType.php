<?php

namespace App\Offer\Form\Type;

use App\Common\Pagination\Form\Type\PaginatedListCriteriaFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OfferPaginatedListCriteriaFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
        ->add('filter', OfferPaginatedListFilterFormType::class);
  }

  public function getParent(): string {
    return PaginatedListCriteriaFormType::class;
  }
}