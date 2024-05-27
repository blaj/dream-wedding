<?php

namespace App\Post\Service;

use App\Post\Dto\PostDetailsDto;
use App\Post\Dto\PostListItemDto;
use App\Post\Entity\Post;
use App\Post\Mapper\PostDetailsDtoMapper;
use App\Post\Mapper\PostListItemDtoMapper;
use App\Post\Repository\PostRepository;

class PostService {

  public static int $homeLatestListLimit = 5;
  public static int $postLatestListLimit = 4;
  public static int $loadMoreListLimit = 8;

  public function __construct(
      private readonly PostRepository $postRepository) {}

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

  public function getOne(int $id): ?PostDetailsDto {
    return PostDetailsDtoMapper::map($this->postRepository->findOneById($id));
  }
}