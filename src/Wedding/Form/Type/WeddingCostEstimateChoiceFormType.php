<?php

namespace App\Wedding\Form\Type;

use App\Common\Utils\FormUtils;
use App\Wedding\Dto\WeddingCostEstimateListItemDto;
use App\Wedding\Service\WeddingCostEstimateService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeddingCostEstimateChoiceFormType extends AbstractType {

  public function __construct(private readonly WeddingCostEstimateService $weddingCostEstimateService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['weddingId', 'userId']);
    $resolver->setAllowedTypes('weddingId', 'int');
    $resolver->setAllowedTypes('userId', 'int');

    $resolver->setDefaults(
        [
            'choice_value' => fn (?WeddingCostEstimateListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?WeddingCostEstimateListItemDto $dto) => $dto?->name,
            'choices' => fn (Options $options) => $this->weddingCostEstimateService->getList(
                $options['weddingId'],
                $options['userId'])]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if (FormUtils::isMultiple($options)) {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (array $ids) => self::getElementsByIds($options['choices'], $ids),
              fn (array $dtos) => array_map(fn (?WeddingCostEstimateListItemDto $dto) => $dto?->id, $dtos)));
    } else {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (?int $id) => self::getElementById($options['choices'], $id),
              fn (?WeddingCostEstimateListItemDto $dto) => $dto?->id));
    }
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<WeddingCostEstimateListItemDto> $elements
   */
  private static function getElementById(array $elements, ?int $id): ?WeddingCostEstimateListItemDto {
    if ($id === null) {
      return null;
    }

    $element = array_filter($elements, fn (WeddingCostEstimateListItemDto $dto) => $dto->id === $id);
    $element = reset($element);

    return $element instanceof WeddingCostEstimateListItemDto ? $element : null;
  }

  /**
   * @param array<WeddingCostEstimateListItemDto> $elements
   * @param array<int> $ids
   *
   * @return array<WeddingCostEstimateListItemDto>
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