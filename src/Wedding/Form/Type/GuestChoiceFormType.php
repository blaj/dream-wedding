<?php

namespace App\Wedding\Form\Type;

use App\Common\Utils\FormUtils;
use App\Wedding\Dto\GuestListItemDto;
use App\Wedding\Service\GuestService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestChoiceFormType extends AbstractType {

  public function __construct(private readonly GuestService $guestService) {}

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setRequired(['weddingId', 'userId']);
    $resolver->setAllowedTypes('weddingId', 'int');
    $resolver->setAllowedTypes('userId', 'int');

    $resolver->setDefaults(
        [
            'choice_value' => fn (?GuestListItemDto $dto) => $dto?->id,
            'choice_label' => fn (?GuestListItemDto $dto) => $dto !== null
                ? $dto->firstName . ' ' . $dto->lastName
                : null,
            'choices' => fn (Options $options) => $this->guestService->getList(
                $options['weddingId'],
                $options['userId'])]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if (FormUtils::isMultiple($options)) {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (array $ids) => self::getElementsByIds($options['choices'], $ids),
              fn (array $dtos) => array_map(fn (?GuestListItemDto $dto) => $dto?->id, $dtos)));
    } else {
      $builder->addModelTransformer(
          new CallbackTransformer(
          /** @phpstan-ignore-next-line */
              fn (?int $id) => self::getElementById($options['choices'], $id),
              fn (?GuestListItemDto $dto) => $dto?->id));
    }
  }

  public function getParent(): string {
    return ChoiceType::class;
  }

  /**
   * @param array<GuestListItemDto> $elements
   */
  private static function getElementById(array $elements, ?int $id): ?GuestListItemDto {
    if ($id === null) {
      return null;
    }

    $element = array_filter($elements, fn (GuestListItemDto $dto) => $dto->id === $id);
    $element = reset($element);

    return $element instanceof GuestListItemDto ? $element : null;
  }

  /**
   * @param array<GuestListItemDto> $elements
   * @param array<int> $ids
   *
   * @return array<GuestListItemDto>
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