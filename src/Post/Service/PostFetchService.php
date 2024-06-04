<?php

namespace App\Post\Service;

use App\Post\Entity\Post;
use App\Post\Repository\PostRepository;
use Doctrine\ORM\EntityNotFoundException;

class PostFetchService {

  public function __construct(private readonly PostRepository $postRepository) {}

  public function fetchPost(int $id): Post {
    return $this->postRepository->findOneById($id)
        ??
        throw new EntityNotFoundException('Post not found');
  }
}