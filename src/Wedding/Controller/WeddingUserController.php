<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Const\TranslationConst;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\WeddingUserInviteRequest;
use App\Wedding\Form\Type\WeddingUserInviteFormType;
use App\Wedding\Form\Type\WeddingUserUpdateFormType;
use App\Wedding\Service\WeddingService;
use App\Wedding\Service\WeddingUserInviteService;
use App\Wedding\Service\WeddingUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/{_locale}/wedding/{weddingId}/user', name: 'wedding_user_', requirements: ['weddingId' => '\d+', '_locale' => TranslationConst::availableLocales])]
class WeddingUserController extends AbstractController {

  public function __construct(
      private readonly WeddingService $weddingService,
      private readonly WeddingUserService $weddingUserService,
      private readonly WeddingUserInviteService $weddingUserInviteService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $weddingId, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/wedding-user/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'weddingUsersListItemDto' => $this->weddingUserService->getList($weddingId),
            'weddingUserInvitesListItemDto' => $this->weddingUserInviteService->getList(
                $weddingId,
                $userData->getUserId())]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $weddingUserDetailsDto = $this->weddingUserService->getOne($id, $userData->getUserId());

    if ($weddingUserDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/wedding-user/details/details.html.twig',
        ['weddingUserDetailsDto' => $weddingUserDetailsDto, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/update', name: 'update', requirements: ['id' => '\d+'], methods: [
      'GET',
      'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $weddingUserUpdateRequest =
        $this->weddingUserService->getUpdateRequest($id, $userData->getUserId());

    if ($weddingUserUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            WeddingUserUpdateFormType::class,
            $weddingUserUpdateRequest,
            ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->weddingUserService->update($id, $weddingUserUpdateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('wedding-user-updated-successfully'));

      return $this->redirectToRoute(
          'wedding_user_details',
          ['weddingId' => $weddingId, 'id' => $id]);
    }

    return $this->render(
        'wedding/wedding-user/update/update.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $weddingId, int $id, UserData $userData): Response {
    $this->weddingUserService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('wedding-user-deleted-successfully'));

    return $this->redirectToRoute('wedding_user_list', ['weddingId' => $weddingId]);
  }

  #[Route(path: '/invite', name: 'invite', methods: ['GET', 'POST'])]
  public function invite(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            WeddingUserInviteFormType::class,
            $weddingUserInviteRequest = new WeddingUserInviteRequest());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->weddingUserInviteService->invite(
          $weddingId,
          $weddingUserInviteRequest,
          $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('wedding-user-invite-send-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('wedding_user_list', ['weddingId' => $weddingId]),
          $this->redirectToRoute('wedding_user_invite', ['weddingId' => $weddingId]));
    }

    return $this->render(
        'wedding/wedding-user/invite/invite.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/resend-invite-email',
      name: 'resend_invite_email',
      requirements: ['id' => '\d+'],
      methods: ['GET'])]
  public function resendInviteEmail(int $weddingId, int $id, UserData $userData): Response {
    $this->weddingUserInviteService->resendInviteEmail($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('wedding-user-invite-resend-successfully'));

    return $this->redirectToRoute('wedding_user_list', ['weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/delete-invite',
      name: 'delete_invite',
      requirements: ['id' => '\d+'],
      methods: ['DELETE'])]
  public function deleteInvite(int $weddingId, int $id, UserData $userData): Response {
    $this->weddingUserInviteService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('wedding-user-invite-deleted-successfully'));

    return $this->redirectToRoute('wedding_user_list', ['weddingId' => $weddingId]);
  }
}