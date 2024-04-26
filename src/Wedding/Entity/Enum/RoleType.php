<?php

namespace App\Wedding\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum RoleType: string implements TranslatableInterface {

  case OWNER = 'OWNER';
  case WRITE = 'WRITE';
  case READ = 'READ';

  public function trans(TranslatorInterface $translator, string $locale = null): string {
    return $translator->trans(strtolower($this->value), locale: $locale);
  }
}