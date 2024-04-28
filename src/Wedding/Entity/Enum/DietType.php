<?php

namespace App\Wedding\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum DietType: string implements TranslatableInterface {

  case OMNIVOROUS = 'OMNIVOROUS';
  case VEGETARIANISM = 'VEGETARIANISM';
  case VEGANISM = 'VEGANISM';

  public function trans(TranslatorInterface $translator, string $locale = null): string {
    return $translator->trans(strtolower($this->value), locale: $locale);
  }
}