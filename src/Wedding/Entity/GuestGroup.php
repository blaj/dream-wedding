<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Wedding\Repository\GuestGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: GuestGroupRepository::class)]
#[Table(name: 'guest_group', schema: 'wedding')]
class GuestGroup extends AuditingEntity {

  #[Column(name: 'name', type: Types::STRING, length: 200, nullable: false)]
  private string $name;

  #[Column(name: 'description', type: Types::TEXT, nullable: true)]
  private ?string $description = null;

  #[ManyToOne(targetEntity: Wedding::class, fetch: 'LAZY', inversedBy: 'guestGroups')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Wedding $wedding;

  /**
   * @var Collection<int, Guest>
   */
  #[ManyToMany(targetEntity: Guest::class, mappedBy: 'groups', fetch: 'LAZY')]
  private Collection $guests;

  public function __construct() {
    $this->guests = new ArrayCollection();
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

  public function getWedding(): Wedding {
    return $this->wedding;
  }

  public function setWedding(Wedding $wedding): self {
    $this->wedding = $wedding;

    return $this;
  }

  /**
   * @return Collection<int, Guest>
   */
  public function getGuests(): Collection {
    return $this->guests;
  }

  /**
   * @param Collection<int, Guest> $guests
   */
  public function setGuests(Collection $guests): GuestGroup {
    $this->guests = $guests;

    return $this;
  }

  public function addGuest(Guest $guest): self {
    if (!$this->guests->contains($guest)) {
      $this->guests->add($guest);
    }

    return $this;
  }

  public function removeGuest(Guest $guest): self {
    $this->guests->removeElement($guest);

    return $this;
  }
}