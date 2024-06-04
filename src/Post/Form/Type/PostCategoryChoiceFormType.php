<?php

namespace App\Post\Form\Type;

use App\Common\Utils\FormUtils;
use App\Post\Dto\PostCategoryListItemDto;
use App\Post\Service\PostCategoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostCategoryChoiceFormType extends AbstractType {

  public function __construct(private readonly PostCategoryService $postCategoryService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults(
        [
            'choice_value' => fn (?PostCategoryListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?PostCategoryListItemDto $dto) => $dto?->name,
            'choices' => $this->postCategoryService->getList()]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if (FormUtils::isMultiple($options)) {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (array $ids) => self::getElementsByIds($options['choices'], $ids),
              fn (array $dtos) => array_map(fn (?PostCategoryListItemDto $dto) => $dto?->id,
                  $dtos)));
    } else {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (?int $id) => self::getElementById($options['choices'], $id),
              fn (?PostCategoryListItemDto $dto) => $dto?->id));
    }
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<PostCategoryListItemDto> $elements
   */
  private static function getElementById(array $elements, ?int $id): ?PostCategoryListItemDto {
    if ($id === null) {
      return null;
    }

    $element = array_filter($elements, fn (PostCategoryListItemDto $dto) => $dto->id === $id);
    $element = reset($element);

    return $element instanceof PostCategoryListItemDto ? $element : null;
  }

  /**
   * @param array<PostCategoryListItemDto> $elements
   * @param array<int> $ids
   *
   * @return array<PostCategoryListItemDto>
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