<?php

namespace App\Post\Form\Type;

use App\Common\Utils\FormUtils;
use App\Post\Dto\PostTagListItemDto;
use App\Post\Service\PostTagService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostTagChoiceFormType extends AbstractType {

  public function __construct(private readonly PostTagService $postTagService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(
        [
            'choice_value' => fn (?PostTagListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?PostTagListItemDto $dto) => $dto?->name,
            'choices' => $this->postTagService->getList()]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if (FormUtils::isMultiple($options)) {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (array $ids) => self::getElementsByIds($options['choices'], $ids),
              fn (array $dtos) => array_map(fn (?PostTagListItemDto $dto) => $dto?->id,
                  $dtos)));
    } else {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (?int $id) => self::getElementById($options['choices'], $id),
              fn (?PostTagListItemDto $dto) => $dto?->id));
    }
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<PostTagListItemDto> $elements
   */
  private static function getElementById(array $elements, ?int $id): ?PostTagListItemDto {
    if ($id === null) {
      return null;
    }

    $element = array_filter($elements, fn (PostTagListItemDto $dto) => $dto->id === $id);
    $element = reset($element);

    return $element instanceof PostTagListItemDto ? $element : null;
  }

  /**
   * @param array<PostTagListItemDto> $elements
   * @param array<int> $ids
   *
   * @return array<PostTagListItemDto>
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