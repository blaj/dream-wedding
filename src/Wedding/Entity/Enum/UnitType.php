<?php

namespace App\Wedding\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum UnitType: string implements TranslatableInterface {

  case PIECE = 'PIECE';
  case SERVICE = 'SERVICE';
  case OTHER = 'OTHER';

  public function trans(TranslatorInterface $translator, string $locale = null): string {
    return $translator->trans(strtolower($this->value), locale: $locale);
  }
}
