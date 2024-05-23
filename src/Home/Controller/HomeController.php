<?php

namespace App\Home\Controller;

use App\Post\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'home_')]
class HomeController extends AbstractController {

  public function __construct(private readonly PostService $postService) {}

  #[Route(path: '/', name: 'index', methods: ['GET'])]
  public function index(): Response {
    return $this->render(
        'home/index.html.twig',
        ['latestPostsListItemDto' => $this->postService->getLatestList()]);
  }
}