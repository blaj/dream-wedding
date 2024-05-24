<?php

namespace App\User\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Const\TranslationConst;
use App\Security\Dto\UserData;
use App\User\Dto\UserRegisterRequest;
use App\User\Dto\UserSettingsRequest;
use App\User\Form\Type\UserRegisterFormType;
use App\User\Form\Type\UserSettingsFormType;
use App\User\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/{_locale}/user', name: 'user_', requirements: ['_locale' => TranslationConst::availableLocales])]
class UserController extends AbstractController {

  public function __construct(private readonly UserService $userService) {}

  #[IsGranted(new Expression("!is_authenticated()"))]
  #[Route(path: '/register', name: 'register', methods: ['GET', 'POST'])]
  public function register(Request $request): Response {
    $form =
        $this->createForm(
            UserRegisterFormType::class,
            $userRegisterRequest = new UserRegisterRequest(),
            ['method' => 'POST']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->userService->register($userRegisterRequest);

      return $this->redirectToRoute('security_login');
    }

    return $this->render('user/register.html.twig', ['form' => $form]);
  }

  #[IsGranted(new Expression("is_authenticated()"))]
  #[Route(path: '/update-settings', name: 'update_settings', methods: ['GET', 'PUT'])]
  public function updateSettings(Request $request, UserData $userData): Response {
    $userSettingsRequest = (new UserSettingsRequest())->setUserId($userData->getUserId());

    $form =
        $this->createForm(
            UserSettingsFormType::class,
            $userSettingsRequest,
            ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->userService->updateSettings($userSettingsRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('user-settings-updated-successfully'));

      return $this->redirectToRoute('user_update_settings');
    }

    return $this->render('user/settings.html.twig', ['form' => $form]);
  }
}