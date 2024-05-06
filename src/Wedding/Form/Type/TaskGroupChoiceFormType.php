<?php

namespace App\Wedding\Form\Type;

use App\Common\Utils\FormUtils;
use App\Wedding\Dto\TaskGroupListItemDto;
use App\Wedding\Service\TaskGroupService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskGroupChoiceFormType extends AbstractType {

  public function __construct(private readonly TaskGroupService $taskGroupService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['weddingId', 'userId']);
    $resolver->setAllowedTypes('weddingId', 'int');
    $resolver->setAllowedTypes('userId', 'int');

    $resolver->setDefaults(
        [
            'choice_value' => fn (?TaskGroupListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?TaskGroupListItemDto $dto) => $dto?->name,
            'choices' => fn (Options $options) => $this->taskGroupService->getList(
                $options['weddingId'],
                $options['userId'])]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if (FormUtils::isMultiple($options)) {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (array $ids) => self::getElementsByIds($options['choices'], $ids),
              fn (array $dtos) => array_map(fn (?TaskGroupListItemDto $dto) => $dto?->id, $dtos)));
    } else {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (?int $id) => self::getElementById($options['choices'], $id),
              fn (?TaskGroupListItemDto $dto) => $dto?->id));
    }
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<TaskGroupListItemDto> $elements
   */
  private static function getElementById(array $elements, ?int $id): ?TaskGroupListItemDto {
    if ($id === null) {
      return null;
    }

    $element = array_filter($elements, fn (TaskGroupListItemDto $dto) => $dto->id === $id);
    $element = reset($element);

    return $element instanceof TaskGroupListItemDto ? $element : null;
  }

  /**
   * @param array<TaskGroupListItemDto> $elements
   * @param array<int> $ids
   *
   * @return array<TaskGroupListItemDto>
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