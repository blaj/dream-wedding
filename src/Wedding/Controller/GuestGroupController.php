<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Const\TranslationConst;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\GuestGroupCreateRequest;
use App\Wedding\Form\Type\GuestGroupCreateFormType;
use App\Wedding\Form\Type\GuestGroupUpdateFormType;
use App\Wedding\Service\GuestGroupService;
use App\Wedding\Service\WeddingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/{_locale}/wedding/{weddingId}/guest-group', name: 'wedding_guest_group_', requirements: ['weddingId' => '\d+', '_locale' => TranslationConst::availableLocales])]
class GuestGroupController extends AbstractController {

  public function __construct(
      private readonly WeddingService $weddingService,
      private readonly GuestGroupService $guestGroupService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $weddingId, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/guest-group/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'guestGroupsListItemDto' => $this->guestGroupService->getList(
                $weddingId,
                $userData->getUserId())]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $guestGroupDetailsDto = $this->guestGroupService->getOne($id, $userData->getUserId());

    if ($guestGroupDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/guest-group/details/details.html.twig',
        ['guestGroupDetailsDto' => $guestGroupDetailsDto, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            GuestGroupCreateFormType::class,
            $guestGroupCreateRequest = new GuestGroupCreateRequest(),
            ['weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->guestGroupService->create(
          $weddingId,
          $guestGroupCreateRequest,
          $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('guest-group-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('wedding_guest_group_list', ['weddingId' => $weddingId]),
          $this->redirectToRoute('wedding_guest_group_create', ['weddingId' => $weddingId]));
    }

    return $this->render(
        'wedding/guest-group/create/create.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $guestGroupUpdateRequest =
        $this->guestGroupService->getUpdateRequest($id, $userData->getUserId());

    if ($guestGroupUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            GuestGroupUpdateFormType::class,
            $guestGroupUpdateRequest,
            ['method' => 'PUT', 'weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->guestGroupService->update($id, $guestGroupUpdateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('guest-group-updated-successfully'));

      return $this->redirectToRoute(
          'wedding_guest_group_details',
          ['weddingId' => $weddingId, 'id' => $id]);
    }

    return $this->render(
        'wedding/guest-group/update/update.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $weddingId, int $id, UserData $userData): Response {
    $this->guestGroupService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('guest-group-deleted-successfully'));

    return $this->redirectToRoute('wedding_guest_group_list', ['weddingId' => $weddingId]);
  }
}