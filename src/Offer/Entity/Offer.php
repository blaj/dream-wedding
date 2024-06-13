<?php

namespace App\Offer\Entity;

use App\Common\Entity\AuditingEntity;
use App\FileStorage\Entity\LocalFileResource;
use App\Offer\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: OfferRepository::class)]
#[Table(name: 'offer', schema: 'offer')]
class Offer extends AuditingEntity {

  #[Column(name: 'title', type: Types::STRING, length: 200, nullable: false)]
  private string $title;

  #[Column(name: 'content', type: Types::TEXT, nullable: false)]
  private string $content;

  #[JoinColumn(name: 'heading_image_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  #[ManyToOne(targetEntity: LocalFileResource::class, fetch: 'LAZY')]
  private ?LocalFileResource $headingImage = null;

  #[Column(name: 'short_content', type: Types::TEXT, nullable: false)]
  private string $shortContent;

  /**
   * @var Collection<int, OfferCategory>
   */
  #[ManyToMany(targetEntity: OfferCategory::class, inversedBy: 'offers')]
  #[JoinTable(name: 'offers_categories', schema: 'offer')]
  #[JoinColumn(name: 'offer_id', referencedColumnName: 'id')]
  #[InverseJoinColumn(name: 'category_id', referencedColumnName: 'id')]
  private Collection $categories;

  public function __construct() {
    $this->categories = new ArrayCollection();
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

  public function getHeadingImage(): ?LocalFileResource {
    return $this->headingImage;
  }

  public function setHeadingImage(?LocalFileResource $headingImage): self {
    $this->headingImage = $headingImage;

    return $this;
  }

  public function getShortContent(): string {
    return $this->shortContent;
  }

  public function setShortContent(string $shortContent): self {
    $this->shortContent = $shortContent;

    return $this;
  }

  /**
   * @return Collection<int, OfferCategory>
   */
  public function getCategories(): Collection {
    return $this->categories;
  }

  /**
   * @param Collection<int, OfferCategory> $categories
   */
  public function setCategories(Collection $categories): self {
    $this->categories = $categories;

    return $this;
  }

  public function addCategory(OfferCategory $category): self {
    if (!$this->categories->contains($category)) {
      $this->categories->add($category);
    }

    return $this;
  }

  public function removeCategory(OfferCategory $category): self {
    $this->categories->removeElement($category);

    return $this;
  }
}