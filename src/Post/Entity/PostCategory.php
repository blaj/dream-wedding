<?php

namespace App\Post\Entity;

use App\Common\Entity\AuditingEntity;
use App\Post\Repository\PostCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: PostCategoryRepository::class)]
#[Table(name: 'category', schema: 'post')]
class PostCategory extends AuditingEntity {

  #[Column(name: 'name', type: Types::STRING, length: 200, nullable: false)]
  private string $name;

  #[Column(name: 'description', type: Types::TEXT, nullable: true)]
  private ?string $description = null;

  /**
   * @var Collection<int, Post>
   */
  #[ManyToMany(targetEntity: Post::class, mappedBy: 'categories')]
  private Collection $posts;

  public function __construct() {
    $this->posts = new ArrayCollection();
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getDescription(): ?string {
    return $this->description;
  }

  public function setDescription(?string $description): self {
    $this->description = $description;

    return $this;
  }

  /**
   * @return Collection<int, Post>
   */
  public function getPosts(): Collection {
    return $this->posts;
  }

  /**
   * @param Collection<int, Post> $posts
   */
  public function setPosts(Collection $posts): self {
    $this->posts = $posts;

    return $this;
  }

  public function addPost(Post $post): self {
    if (!$this->posts->contains($post)) {
      $this->posts->add($post);
    }

    return $this;
  }

  public function removePost(Post $post): self {
    $this->posts->removeElement($post);

    return $this;
  }
}