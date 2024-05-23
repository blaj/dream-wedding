<?php

namespace App\Offer\Entity;

use App\Common\Entity\AuditingEntity;
use App\Offer\Repository\OfferCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: OfferCategoryRepository::class)]
#[Table(name: 'category', schema: 'offer')]
class OfferCategory extends AuditingEntity {

  #[Column(name: 'name', type: Types::STRING, length: 200, nullable: false)]
  private string $name;

  #[Column(name: 'description', type: Types::TEXT, nullable: true)]
  private ?string $description = null;

  /**
   * @var Collection<int, Offer>
   */
  #[ManyToMany(targetEntity: Offer::class, mappedBy: 'categories')]
  private Collection $offers;

  public function __construct() {
    $this->offers = new ArrayCollection();
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
   * @return Collection<int, Offer>
   */
  public function getOffers(): Collection {
    return $this->offers;
  }

  /**
   * @param Collection<int, Offer> $offers
   */
  public function setOffers(Collection $offers): self {
    $this->offers = $offers;

    return $this;
  }

  public function addOffer(Offer $offer): self {
    if (!$this->offers->contains($offer)) {
      $this->offers->add($offer);
    }

    return $this;
  }

  public function removeOffer(Offer $offer): self {
    $this->offers->removeElement($offer);

    return $this;
  }
}