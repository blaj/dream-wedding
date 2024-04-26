<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Security\Dto\UserData;
use App\Wedding\Service\WeddingUserInviteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/wedding-user-invite', name: 'wedding_user_invite_')]
class WeddingUserInviteAcceptController extends AbstractController {

  public function __construct(
      private readonly WeddingUserInviteService $weddingUserInviteService) {}

  #[Route(path: '/{token}/accept', name: 'accept', requirements: ['token' => '.+'], methods: ['GET'])]
  public function acceptInvite(string $token, UserData $userData): Response {
    $this->weddingUserInviteService->acceptInvite($token, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('wedding-user-invite-accepted-successfully'));

    return $this->redirectToRoute('wedding_list');
  }
}