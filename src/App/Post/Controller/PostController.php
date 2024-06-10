<?php

namespace App\App\Post\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Utils\FormUtils;
use App\Post\Dto\PostCreateRequest;
use App\Post\Form\Type\PostCreateFormType;
use App\Post\Form\Type\PostUpdateFormType;
use App\Post\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted('ROLE_MODERATOR')]
#[Route(path: '/post', name: 'post_')]
class PostController extends AbstractController {

  public function __construct(private readonly PostService $postService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(): Response {
    return $this->render(
        'app/post/list/list.html.twig',
        ['postsListItemDto' => $this->postService->getList()]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(Request $request): Response {
    $form =
        $this->createForm(PostCreateFormType::class, $postCreateRequest = new PostCreateRequest());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->postService->create($postCreateRequest);

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('post-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('app_post_list'),
          $this->redirectToRoute('app_post_create'));
    }

    return $this->render('app/post/create/create.html.twig', ['form' => $form]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $id, Request $request): Response {
    $postDetailsDto = $this->postService->getOne($id);

    if ($postDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    $postUpdateRequest = $this->postService->getUpdateRequest($id);

    if ($postUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form = $this->createForm(PostUpdateFormType::class, $postUpdateRequest, ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->postService->update($id, $postUpdateRequest);

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('post-updated-successfully'));

      return $this->redirectToRoute('app_post_list');
    }

    return $this->render(
        'app/post/update/update.html.twig',
        [
            'form' => $form,
            'postUpdateRequest' => $postUpdateRequest,
            'postDetailsDto' => $postDetailsDto]);
  }

  #[Route(
      path: '/{id}/delete',
      name: 'delete',
      requirements: ['id' => '\d+'],
      methods: ['DELETE'])]
  public function delete(int $id): Response {
    $this->postService->delete($id);

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('post-deleted-successfully'));

    return $this->redirectToRoute('app_post_list');
  }
}