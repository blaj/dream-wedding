<?php

namespace App\Post\Service;

use App\Post\Entity\PostTag;
use App\Post\Repository\PostTagRepository;
use Doctrine\ORM\EntityNotFoundException;

class PostTagFetchService {

  public function __construct(private readonly PostTagRepository $postTagRepository) {}

  public function fetchPostTag(int $id): PostTag {
    return $this->postTagRepository->findOneById($id)
        ??
        throw new EntityNotFoundException('Post tag not found');
  }
}