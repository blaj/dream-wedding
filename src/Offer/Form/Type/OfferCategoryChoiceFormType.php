<?php

namespace App\Offer\Form\Type;

use App\Common\Utils\FormUtils;
use App\Offer\Dto\OfferCategoryListItemDto;
use App\Offer\Service\OfferCategoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferCategoryChoiceFormType extends AbstractType {

  public function __construct(private readonly OfferCategoryService $offerCategoryService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(
        [
            'choice_value' => fn (?OfferCategoryListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?OfferCategoryListItemDto $dto) => $dto?->name,
            'choices' => $this->offerCategoryService->getList()]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if (FormUtils::isMultiple($options)) {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (array $ids) => self::getElementsByIds($options['choices'], $ids),
              fn (array $dtos) => array_map(fn (?OfferCategoryListItemDto $dto) => $dto?->id,
                  $dtos)));
    } else {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (?int $id) => self::getElementById($options['choices'], $id),
              fn (?OfferCategoryListItemDto $dto) => $dto?->id));
    }
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<OfferCategoryListItemDto> $elements
   */
  private static function getElementById(array $elements, ?int $id): ?OfferCategoryListItemDto {
    if ($id === null) {
      return null;
    }

    $element = array_filter($elements, fn (OfferCategoryListItemDto $dto) => $dto->id === $id);
    $element = reset($element);

    return $element instanceof OfferCategoryListItemDto ? $element : null;
  }

  /**
   * @param array<OfferCategoryListItemDto> $elements
   * @param array<int> $ids
   *
   * @return array<OfferCategoryListItemDto>
   */
  private static function getElementsByIds(array $elements, array $ids): array {
    $result = [];

    foreach ($elements as $element) {
      foreach ($ids as $id) {
        if ($id !== $element->id) {
          continue;
        }

        $result[] = $element;
      }
    }

    return $result;
  }
}