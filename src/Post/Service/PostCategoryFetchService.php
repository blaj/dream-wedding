<?php

namespace App\Post\Service;

use App\Post\Entity\PostCategory;
use App\Post\Repository\PostCategoryRepository;
use Doctrine\ORM\EntityNotFoundException;

class PostCategoryFetchService {

  public function __construct(private readonly PostCategoryRepository $postCategoryRepository) {}

  public function fetchPostCategory(int $id): PostCategory {
    return $this->postCategoryRepository->findOneById($id)
        ??
        throw new EntityNotFoundException('Post category not found');
  }
}