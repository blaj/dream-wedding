<?php

namespace App\Post\Entity;

use App\Common\Entity\AuditingEntity;
use App\Post\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: PostRepository::class)]
#[Table(name: 'post', schema: 'post')]
class Post extends AuditingEntity {

  #[Column(name: 'title', type: Types::STRING, length: 200, nullable: false)]
  private string $title;

  #[Column(name: 'content', type: Types::TEXT, nullable: false)]
  private string $content;

  /**
   * @var Collection<int, PostCategory>
   */
  #[ManyToMany(targetEntity: PostCategory::class, inversedBy: 'posts')]
  #[JoinTable(name: 'posts_categories', schema: 'post')]
  #[JoinColumn(name: 'post_id', referencedColumnName: 'id')]
  #[InverseJoinColumn(name: 'category_id', referencedColumnName: 'id')]
  private Collection $categories;

  /**
   * @var Collection<int, PostTag>
   */
  #[ManyToMany(targetEntity: PostTag::class, inversedBy: 'posts')]
  #[JoinTable(name: 'posts_tags', schema: 'post')]
  #[JoinColumn(name: 'post_id', referencedColumnName: 'id')]
  #[InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id')]
  private Collection $tags;

  public function __construct() {
    $this->categories = new ArrayCollection();
    $this->tags = new ArrayCollection();
  }

  public function getTitle(): string {
    return $this->title;
  }

  public function setTitle(string $title): self {
    $this->title = $title;

    return $this;
  }

  public function getContent(): string {
    return $this->content;
  }

  public function setContent(string $content): self {
    $this->content = $content;

    return $this;
  }

  /**
   * @return Collection<int, PostCategory>
   */
  public function getCategories(): Collection {
    return $this->categories;
  }

  /**
   * @param Collection<int, PostCategory> $categories
   */
  public function setCategories(Collection $categories): self {
    $this->categories = $categories;

    return $this;
  }

  public function addCategory(PostCategory $category): self {
    if (!$this->categories->contains($category)) {
      $this->categories->add($category);
    }

    return $this;
  }

  public function removeCategory(PostCategory $category): self {
    $this->categories->removeElement($category);

    return $this;
  }

  /**
   * @return Collection<int, PostTag>
   */
  public function getTags(): Collection {
    return $this->tags;
  }

  /**
   * @param Collection<int, PostTag> $tags
   */
  public function setTags(Collection $tags): self {
    $this->tags = $tags;

    return $this;
  }

  public function addTag(PostTag $tag): self {
    if (!$this->tags->contains($tag)) {
      $this->tags->add($tag);
    }

    return $this;
  }

  public function removeTag(PostTag $tag): self {
    $this->tags->removeElement($tag);

    return $this;
  }
}