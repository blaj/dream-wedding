<?php

namespace App\Post\Service;

use App\Post\Dto\PostCategoryListItemDto;
use App\Post\Entity\PostCategory;
use App\Post\Mapper\PostCategoryListItemDtoMapper;
use App\Post\Repository\PostCategoryRepository;

class PostCategoryService {

  public function __construct(private readonly PostCategoryRepository $postCategoryRepository) {}

  public function getList(): array {
    return array_filter(
        array_map(
            fn (PostCategory $postCategory) => PostCategoryListItemDtoMapper::map($postCategory),
            $this->postCategoryRepository->findAll()),
        fn (?PostCategoryListItemDto $dto) => $dto !== null);
  }
}