<?php

namespace App\Post\Service;

use App\Post\Dto\PostListItemDto;
use App\Post\Entity\Post;
use App\Post\Mapper\PostListItemDtoMapper;
use App\Post\Repository\PostRepository;

class PostService {

  private static int $latestListLimit = 5;

  public function __construct(private readonly PostRepository $postRepository) {}

  /**
   * @return array<PostListItemDto>
   */
  public function getLatestList(): array {
    return array_filter(
        array_map(
            fn (Post $post) => PostListItemDtoMapper::map($post),
            $this->postRepository->findAllOrderByCreatedAtAscLimitByLimit(self::$latestListLimit)),
        fn (?PostListItemDto $dto) => $dto !== null);
  }
}