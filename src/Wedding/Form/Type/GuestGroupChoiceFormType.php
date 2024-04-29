<?php

namespace App\Wedding\Form\Type;

use App\Common\Utils\FormUtils;
use App\Wedding\Dto\GuestGroupListItemDto;
use App\Wedding\Service\GuestGroupService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestGroupChoiceFormType extends AbstractType {

  public function __construct(private readonly GuestGroupService $guestGroupService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['weddingId', 'userId']);
    $resolver->setAllowedTypes('weddingId', 'int');
    $resolver->setAllowedTypes('userId', 'int');

    $resolver->setDefaults(
        [
            'choice_value' => fn (?GuestGroupListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?GuestGroupListItemDto $dto) => $dto?->name,
            'choices' => fn (Options $options) => $this->guestGroupService->getList(
                $options['weddingId'],
                $options['userId'])]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if (FormUtils::isMultiple($options)) {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (array $ids) => self::getElementsByIds($options['choices'], $ids),
              fn (array $dtos) => array_map(fn (?GuestGroupListItemDto $dto) => $dto?->id, $dtos)));
    } else {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (?int $id) => self::getElementById($options['choices'], $id),
              fn (?GuestGroupListItemDto $dto) => $dto?->id));
    }
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<GuestGroupListItemDto> $elements
   */
  private static function getElementById(array $elements, ?int $id): ?GuestGroupListItemDto {
    if ($id === null) {
      return null;
    }

    $element = array_filter($elements, fn (GuestGroupListItemDto $dto) => $dto->id === $id);
    $element = reset($element);

    return $element instanceof GuestGroupListItemDto ? $element : null;
  }

  /**
   * @param array<GuestGroupListItemDto> $elements
   * @param array<int> $ids
   *
   * @return array<GuestGroupListItemDto>
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