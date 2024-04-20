<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Wedding\Repository\WeddingRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: WeddingRepository::class)]
#[Table(name: 'wedding', schema: 'wedding')]
class Wedding extends AuditingEntity {

  #[Column(name: 'name', type: Types::STRING, length: 100, nullable: false)]
  private string $name;

  #[Column(name: 'on_date', type: Types::DATE_IMMUTABLE, nullable: false)]
  private DateTimeImmutable $onDate;

  /**
   * @var Collection<int, Guest>
   */
  #[OneToMany(targetEntity: Guest::class, mappedBy: 'wedding', fetch: 'LAZY')]
  private Collection $guests;

  /**
   * @var Collection<int, WeddingUser>
   */
  #[OneToMany(targetEntity: WeddingUser::class, mappedBy: 'wedding', fetch: 'LAZY')]
  private Collection $weddingUsers;

  public function __construct() {
    $this->guests = new ArrayCollection();
    $this->weddingUsers = new ArrayCollection();
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getOnDate(): DateTimeImmutable {
    return $this->onDate;
  }

  public function setOnDate(DateTimeImmutable $onDate): self {
    $this->onDate = $onDate;

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
  public function setGuests(Collection $guests): self {
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

  /**
   * @return Collection<int, WeddingUser>
   */
  public function getWeddingUsers(): Collection {
    return $this->weddingUsers;
  }

  /**
   * @param Collection<int, WeddingUser> $weddingUsers
   */
  public function setWeddingUsers(Collection $weddingUsers): self {
    $this->weddingUsers = $weddingUsers;

    return $this;
  }

  public function addWeddingUser(WeddingUser $weddingUser): self {
    if (!$this->weddingUsers->contains($weddingUser)) {
      $this->weddingUsers->add($weddingUser);
    }

    return $this;
  }

  public function removeWeddingUser(WeddingUser $weddingUser): self {
    $this->weddingUsers->removeElement($weddingUser);

    return $this;
  }
}