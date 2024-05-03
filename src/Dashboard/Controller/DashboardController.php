<?php

namespace App\Dashboard\Controller;

use App\Security\Dto\UserData;
use App\Wedding\Service\WeddingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: '/dashboard', name: 'dashboard_')]
class DashboardController extends AbstractController {

  public function __construct(private readonly WeddingService $weddingService) {}

  #[IsGranted(new Expression("is_authenticated()"))]
  #[Route(path: '/', name: 'index', methods: ['GET'])]
  public function index(UserData $userData): Response {
    return $this->render(
        'dashboard/index.html.twig',
        ['weddingNearestDto' => $this->weddingService->getOneNearest($userData->getUserId())]);
  }
}