<?php

namespace App\Wedding\Form\Type;

use App\Common\Utils\FormUtils;
use App\Wedding\Dto\CostEstimateGroupListItemDto;
use App\Wedding\Service\CostEstimateGroupService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CostEstimateGroupChoiceFormType extends AbstractType {

  public function __construct(private readonly CostEstimateGroupService $costEstimateGroupService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['weddingId', 'userId']);
    $resolver->setAllowedTypes('weddingId', 'int');
    $resolver->setAllowedTypes('userId', 'int');

    $resolver->setDefaults(
        [
            'choice_value' => fn (?CostEstimateGroupListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?CostEstimateGroupListItemDto $dto) => $dto?->name,
            'choices' => fn (Options $options) => $this->costEstimateGroupService->getList(
                $options['weddingId'],
                $options['userId'])]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if (FormUtils::isMultiple($options)) {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (array $ids) => self::getElementsByIds($options['choices'], $ids),
              fn (array $dtos) => array_map(fn (?CostEstimateGroupListItemDto $dto) => $dto?->id, $dtos)));
    } else {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (?int $id) => self::getElementById($options['choices'], $id),
              fn (?CostEstimateGroupListItemDto $dto) => $dto?->id));
    }
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<CostEstimateGroupListItemDto> $elements
   */
  private static function getElementById(array $elements, ?int $id): ?CostEstimateGroupListItemDto {
    if ($id === null) {
      return null;
    }

    $element = array_filter($elements, fn (CostEstimateGroupListItemDto $dto) => $dto->id === $id);
    $element = reset($element);

    return $element instanceof CostEstimateGroupListItemDto ? $element : null;
  }

  /**
   * @param array<CostEstimateGroupListItemDto> $elements
   * @param array<int> $ids
   *
   * @return array<CostEstimateGroupListItemDto>
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