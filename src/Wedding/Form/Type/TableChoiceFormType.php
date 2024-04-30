<?php

namespace App\Wedding\Form\Type;

use App\Common\Utils\FormUtils;
use App\Wedding\Dto\TableListItemDto;
use App\Wedding\Service\TableService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableChoiceFormType extends AbstractType {

  public function __construct(private readonly TableService $tableService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['weddingId', 'userId']);
    $resolver->setAllowedTypes('weddingId', 'int');
    $resolver->setAllowedTypes('userId', 'int');

    $resolver->setDefaults(
        [
            'choice_value' => fn (?TableListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?TableListItemDto $dto) => $dto?->name,
            'choices' => fn (Options $options) => $this->tableService->getList(
                $options['weddingId'],
                $options['userId'])]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if (FormUtils::isMultiple($options)) {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (array $ids) => self::getElementsByIds($options['choices'], $ids),
              fn (array $dtos) => array_map(fn (?TableListItemDto $dto) => $dto?->id, $dtos)));
    } else {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (?int $id) => self::getElementById($options['choices'], $id),
              fn (?TableListItemDto $dto) => $dto?->id));
    }
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<TableListItemDto> $elements
   */
  private static function getElementById(array $elements, ?int $id): ?TableListItemDto {
    if ($id === null) {
      return null;
    }

    $element = array_filter($elements, fn (TableListItemDto $dto) => $dto->id === $id);
    $element = reset($element);

    return $element instanceof TableListItemDto ? $element : null;
  }

  /**
   * @param array<TableListItemDto> $elements
   * @param array<int> $ids
   *
   * @return array<TableListItemDto>
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