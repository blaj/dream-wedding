<?php

namespace App\Security\Controller;

use App\Common\Const\TranslationConst;
use App\Security\Form\Type\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/{_locale}', name: 'security_', requirements: ['_locale' => TranslationConst::availableLocales])]
class SecurityController extends AbstractController {

  #[IsGranted(new Expression("!is_authenticated()"))]
  #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
  public function login(AuthenticationUtils $authenticationUtils): Response {
    $form =
        $this->createForm(
            LoginFormType::class,
            null,
            ['lastUsername' => $authenticationUtils->getLastUsername()]);

    return $this->render(
        'security/login.html.twig',
        [
            'lastUsername' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'form' => $form]);
  }

  #[IsGranted(new Expression("is_authenticated()"))]
  #[Route(path: '/logout', name: 'logout', methods: ['GET', 'POST'])]
  public function logout(): void {
    throw new BadRequestHttpException('Action is not allowed!');
  }
}