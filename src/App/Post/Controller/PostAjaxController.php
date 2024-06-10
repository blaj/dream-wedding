<?php

namespace App\App\Post\Controller;

use App\App\Post\Dto\DeleteImageRequest;
use App\Common\ValueResolver\FileRequestValueResolver;
use App\Post\Service\PostService;
use App\Security\Dto\UserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/post-ajax', name: 'post_ajax_')]
class PostAjaxController extends AbstractController {

  public function __construct(private readonly PostService $postService) {}

  #[Route(path: '/upload-image', name: 'upload_image', options: ['expose' => true], methods: ['POST'])]
  public function uploadImage(
      #[MapRequestPayload(resolver: FileRequestValueResolver::class)] UploadedFile $file): Response {
    return $this->json(['url' => $this->postService->uploadImage($file)]);
  }

  #[Route(path: '/delete-image', name: 'delete_image', options: ['expose' => true], methods: ['DELETE'])]
  public function deleteImage(
      #[MapRequestPayload] DeleteImageRequest $deleteImageRequest,
      UserData $userData): Response {
    $this->postService->deleteImage($deleteImageRequest->path, $userData->getUserId());

    return $this->json(null);
  }
}