<?php

namespace App\Post\Service;

use App\FileStorage\Entity\LocalFileResource;
use App\FileStorage\Repository\LocalFileResourceRepository;
use App\FileStorage\Service\FileStorageService;
use App\Post\Dto\PostCreateRequest;
use App\Post\Dto\PostDetailsDto;
use App\Post\Dto\PostListItemDto;
use App\Post\Dto\PostUpdateRequest;
use App\Post\Entity\Post;
use App\Post\Entity\PostCategory;
use App\Post\Entity\PostTag;
use App\Post\Mapper\PostDetailsDtoMapper;
use App\Post\Mapper\PostListItemDtoMapper;
use App\Post\Mapper\PostUpdateRequestMapper;
use App\Post\Repository\PostRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostService {

  public static int $homeLatestListLimit = 5;
  public static int $postLatestListLimit = 4;
  public static int $loadMoreListLimit = 8;

  public function __construct(
      private readonly PostRepository $postRepository,
      private readonly LocalFileResourceRepository $localFileResourceRepository,
      private readonly PostFetchService $postFetchService,
      private readonly PostCategoryFetchService $postCategoryFetchService,
      private readonly PostTagFetchService $postTagFetchService,
      private readonly FileStorageService $fileStorageService) {}

  public function hasNextLoadMoreListPage(int $no): bool {
    return $this->postRepository->countAll() > ($no + 1) * self::$loadMoreListLimit;
  }

  /**
   * @return array<PostListItemDto>
   */
  public function getLoadMoreList(int $no): array {
    return array_filter(
        array_map(
            fn (Post $post) => PostListItemDtoMapper::map($post),
            $this->postRepository->findAllLimitByLimitAndOffsetByOffset(
                self::$loadMoreListLimit,
                $no * self::$loadMoreListLimit)),
        fn (?PostListItemDto $dto) => $dto !== null);
  }

  /**
   * @return array<PostListItemDto>
   */
  public function getLatestList(int $latestListLimit): array {
    return array_filter(
        array_map(
            fn (Post $post) => PostListItemDtoMapper::map($post),
            $this->postRepository->findAllOrderByCreatedAtAscLimitByLimit($latestListLimit)),
        fn (?PostListItemDto $dto) => $dto !== null);
  }

  /**
   * @return array<PostListItemDto>
   */
  public function getList(): array {
    return array_filter(
        array_map(fn (Post $post) => PostListItemDtoMapper::map($post),
            $this->postRepository->findAll()),
        fn (?PostListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id): ?PostDetailsDto {
    return PostDetailsDtoMapper::map($this->postRepository->findOneById($id));
  }

  public function getUpdateRequest(int $id): ?PostUpdateRequest {
    return PostUpdateRequestMapper::map($this->postRepository->findOneById($id));
  }

  public function create(PostCreateRequest $postCreateRequest): void {
    $categories =
        array_map(fn (int $id) => $this->postCategoryFetchService->fetchPostCategory($id),
            $postCreateRequest->getCategories());
    $tags =
        array_map(fn (int $id) => $this->postTagFetchService->fetchPostTag($id),
            $postCreateRequest->getTags());

    $post = (new Post())
        ->setTitle($postCreateRequest->getTitle())
        ->setContent(stripslashes($postCreateRequest->getContent()))
        ->setShortContent($postCreateRequest->getShortContent());

    array_walk($categories, fn (PostCategory $postCategory) => $post->addCategory($postCategory));
    array_walk($tags, fn (PostTag $postTag) => $post->addTag($postTag));

    $this->postRepository->save($post);

    $this->addHeadingImage($postCreateRequest->getHeadingImage(), $post);
  }

  public function update(int $id, PostUpdateRequest $postUpdateRequest): void {
    $post = $this->postFetchService->fetchPost($id);

    $addedCategories =
        array_map(
            fn (int $categoryId) => $this->postCategoryFetchService->fetchPostCategory($categoryId),
            array_filter(
                $postUpdateRequest->getCategories(),
                fn (int $categoryId) => !in_array(
                    $categoryId,
                    array_map(fn (PostCategory $postCategory) => $postCategory->getId(),
                        $post->getCategories()->toArray()),
                    true)));
    $removedCategories =
        array_filter(
            $post->getCategories()->toArray(),
            fn (PostCategory $postCategory) => !in_array(
                $postCategory->getId(),
                $postUpdateRequest->getCategories(),
                true));

    $addedTags =
        array_map(
            fn (int $tagId) => $this->postTagFetchService->fetchPostTag($tagId),
            array_filter(
                $postUpdateRequest->getTags(),
                fn (int $tagId) => !in_array(
                    $tagId,
                    array_map(fn (PostTag $postTag) => $postTag->getId(),
                        $post->getTags()->toArray()),
                    true)));
    $removedTags =
        array_filter(
            $post->getTags()->toArray(),
            fn (PostTag $postTag) => !in_array(
                $postTag->getId(),
                $postUpdateRequest->getTags(),
                true));

    $post->setTitle($postUpdateRequest->getTitle())
        ->setContent($postUpdateRequest->getContent())
        ->setShortContent($postUpdateRequest->getShortContent());

    array_walk($addedCategories, fn (PostCategory $postCategory) => $post->addCategory($postCategory));
    array_walk($removedCategories, fn (PostCategory $postCategory) => $post->removeCategory($postCategory));

    array_walk($addedTags, fn (PostTag $postTag) => $post->addTag($postTag));
    array_walk($removedTags, fn (PostTag $postTag) => $post->removeTag($postTag));

    $this->postRepository->save($post);

    $this->addHeadingImage($postUpdateRequest->getHeadingImage(), $post);
  }

  public function delete(int $id): void {
    $this->postRepository->softDeleteById($this->postFetchService->fetchPost($id)->getId());
  }

  private function addHeadingImage(?UploadedFile $uploadedFile, Post $post): void {
    if ($uploadedFile !== null) {
      $path = $this->fileStorageService->postPath($post->getId());
      $size = $uploadedFile->getSize();
      $fullPath = $this->fileStorageService->uploadFile($uploadedFile, $path);

      $headingImage = (new LocalFileResource())
          ->setContentType($uploadedFile->getClientMimeType())
          ->setPath($fullPath)
          ->setOriginalFileName($uploadedFile->getClientOriginalName())
          ->setSize($size);
      $this->localFileResourceRepository->save($headingImage);

      $post->setHeadingImage($headingImage);
      $this->postRepository->save($post);
    }
  }
}