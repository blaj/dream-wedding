<?php

namespace App\Post\Controller;

use App\Common\Const\TranslationConst;
use App\Post\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

#[Route(path: '/{_locale}/post', name: 'post_', requirements: ['_locale' => TranslationConst::availableLocales])]
class PostController extends AbstractController {

  public function __construct(private readonly PostService $postService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(Request $request): Response {
    return $this->render(
        'post/list/list.html.twig',
        [
            'latestPostsListItemDto' => $this->postService->getLatestList(
                PostService::$postLatestListLimit),
            'postsListItemDto' => $this->postService->getLoadMoreList(0),
            'hasNextLoadMoreListPage' => $this->postService->hasNextLoadMoreListPage(0)]);
  }

  #[Route(path: '/load-more', name: 'load_more', methods: ['GET'])]
  public function loadMore(#[MapQueryParameter] int $no, Request $request): Response {
    $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

    return $this->renderBlock(
        'post/list/list.html.twig',
        'success_load_more_stream',
        [
            'postsListItemDto' => $this->postService->getLoadMoreList($no),
            'hasNextLoadMoreListPage' => $this->postService->hasNextLoadMoreListPage($no),
            'no' => $no]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $id): Response {
    $postDetailsDto = $this->postService->getOne($id);

    if ($postDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render('post/details/details.html.twig', ['postDetailsDto' => $postDetailsDto]);
  }
}