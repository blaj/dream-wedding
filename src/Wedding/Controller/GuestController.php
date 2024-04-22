<?php

namespace App\Wedding\Controller;

use App\Wedding\Service\GuestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/wedding', name: 'wedding_')]
class GuestController extends AbstractController {

  public function __construct(private readonly GuestService $guestService) {}
}