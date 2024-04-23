<?php

namespace App\Common\Twig\Extension;

use App\Security\Dto\UserData;
use App\Wedding\Dto\WeddingListItemDto;
use App\Wedding\Service\WeddingService;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GetWeddingsExtension extends AbstractExtension {

  public function __construct(private readonly Security $security, private readonly WeddingService $weddingService) {}

  public function getFunctions(): array {
    return [
        new TwigFunction('getWeddings', [$this, 'getWeddings'])
    ];
  }

  /**
   * @return array<WeddingListItemDto>
   */
  public function getWeddings(): array {
    $userData = $this->security->getUser();

    if (!$userData instanceof UserData) {
      return [];
    }

    return $this->weddingService->getList($userData->getUserId());
  }
}