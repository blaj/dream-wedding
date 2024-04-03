<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/test', name: 'test_')]
class TestController extends AbstractController {

  #[Route(path: '', name: 'test')]
  public function test(): Response {
    return $this->render('test/test.html.twig');
  }
}