<?php

namespace App\Wedding\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TableType: string implements TranslatableInterface {

  case SQUARE = 'SQUARE';

  case ROUND = 'ROUND';

  public function trans(TranslatorInterface $translator, string $locale = null): string {
    return $translator->trans(strtolower($this->value), locale: $locale);
  }
}