<?php

namespace App\Common\Twig\Extension;

use App\Wedding\Entity\Enum\TableType;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TableTypeIconExtension extends AbstractExtension {

  public function getFilters(): array {
    return [
        new TwigFilter('tableTypeIcon', [$this, 'tableTypeIcon'])
    ];
  }

  public function tableTypeIcon(TableType $type): string {
    return match ($type) {
      TableType::ROUND => 'bi bi-circle-fill',
      TableType::RECTANGULAR => 'bi bi-square',
      TableType::SQUARE => 'bi bi-square-fill'
    };
  }
}