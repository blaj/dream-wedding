<?php

namespace App\Wedding\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum GuestType: string implements TranslatableInterface {

  case GUEST = 'GUEST';
  case BRIDEGROOM = 'BRIDEGROOM';
  case BRIDE = 'BRIDE';
  case CHILD = 'CHILD';

  public function trans(TranslatorInterface $translator, string $locale = null): string {
    return $translator->trans(strtolower($this->value), locale: $locale);
  }
}