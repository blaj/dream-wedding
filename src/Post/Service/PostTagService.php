<?php

namespace App\Post\Service;

use App\Post\Dto\PostTagListItemDto;
use App\Post\Entity\PostTag;
use App\Post\Mapper\PostTagListItemDtoMapper;
use App\Post\Repository\PostTagRepository;

class PostTagService {

  public function __construct(private readonly PostTagRepository $postTagRepository) {}

  /**
   * @return array<PostTagListItemDto>
   */
  public function getList(): array {
    return array_filter(
        array_map(fn (PostTag $postTag) => PostTagListItemDtoMapper::map($postTag),
            $this->postTagRepository->findAll()),
        fn (?PostTagListItemDto $dto) => $dto !== null);
  }
}