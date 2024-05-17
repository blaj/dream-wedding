<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Dto\GroupSimpleCreateRequest;
use App\Common\Form\Type\GroupSimpleCreateFormType;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\GuestCreateRequest;
use App\Wedding\Form\Type\GuestCreateFormType;
use App\Wedding\Form\Type\GuestUpdateFormType;
use App\Wedding\Service\GuestGroupBuilderService;
use App\Wedding\Service\GuestGroupService;
use App\Wedding\Service\GuestService;
use App\Wedding\Service\WeddingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\UX\Turbo\TurboBundle;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/wedding/{weddingId}/guest', name: 'wedding_guest_', requirements: ['weddingId' => '\d+'])]
class GuestController extends AbstractController {

  public function __construct(
      private readonly WeddingService $weddingService,
      private readonly GuestService $guestService,
      private readonly GuestGroupService $guestGroupService,
      private readonly GuestGroupBuilderService $guestGroupBuilderService) {}

  #[Route(path: '/', name: 'list', methods: ['GET', 'POST'])]
  public function list(int $weddingId, Request $request, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    $groupSimpleCreateForm =
        $this->createForm(
            GroupSimpleCreateFormType::class,
            $groupSimpleCreateRequest = new GroupSimpleCreateRequest());
    $emptyGroupSimpleCreateForm = clone $groupSimpleCreateForm;
    $groupSimpleCreateForm->handleRequest($request);

    if ($groupSimpleCreateForm->isSubmitted() && $groupSimpleCreateForm->isValid()) {
      if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        $createdGroupId =
            $this->guestGroupService->simpleCreate(
                $weddingId,
                $groupSimpleCreateRequest,
                $userData->getUserId());

        return $this->renderBlock(
            'wedding/guest/list/list.html.twig',
            'success_create_group_stream',
            [
                'weddingId' => $weddingId,
                'groupSimpleCreateForm' => $emptyGroupSimpleCreateForm,
                'createdGuestGroupDetailsDto' => $this->guestGroupService->getOne(
                    $createdGroupId,
                    $userData->getUserId())]);
      }

      return $this->redirectToRoute(
          'wedding_guest_list',
          ['weddingId' => $weddingId],
          Response::HTTP_SEE_OTHER);
    }

    return $this->render(
        'wedding/guest/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'ungroupedGuestsListItemDto' => $this->guestService->getUngroupedList(
                $weddingId,
                $userData->getUserId()),
            'guestGroupBuildDto' => $this->guestGroupBuilderService->build(
                $weddingId,
                $userData->getUserId()),
            'groupSimpleCreateForm' => $groupSimpleCreateForm]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $guestDetailsDto = $this->guestService->getOne($id, $userData->getUserId());

    if ($guestDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/guest/details/details.html.twig',
        ['guestDetailsDto' => $guestDetailsDto, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            GuestCreateFormType::class,
            $guestCreateRequest = new GuestCreateRequest(),
            ['weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->guestService->create($weddingId, $guestCreateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('guest-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('wedding_guest_list', ['weddingId' => $weddingId]),
          $this->redirectToRoute('wedding_guest_create', ['weddingId' => $weddingId]));
    }

    return $this->render(
        'wedding/guest/create/create.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $guestUpdateRequest = $this->guestService->getUpdateRequest($id, $userData->getUserId());

    if ($guestUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            GuestUpdateFormType::class,
            $guestUpdateRequest,
            ['method' => 'PUT', 'weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->guestService->update($id, $guestUpdateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('guest-updated-successfully'));

      return $this->redirectToRoute(
          'wedding_guest_details',
          ['weddingId' => $weddingId, 'id' => $id]);
    }

    return $this->render(
        'wedding/guest/update/update.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $weddingId, int $id, UserData $userData): Response {
    $this->guestService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('guest-deleted-successfully'));

    return $this->redirectToRoute('wedding_guest_list', ['weddingId' => $weddingId]);
  }
}