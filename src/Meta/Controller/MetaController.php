<?php

namespace App\Meta\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'meta_')]
class MetaController extends AbstractController {

  #[Route(path: '/', name: 'index', methods: ['GET'])]
  public function index(): Response {
    return $this->redirectToRoute('home_index');
  }
}