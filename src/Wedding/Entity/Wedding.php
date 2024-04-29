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
use Money\Money;

#[Entity(repositoryClass: WeddingRepository::class)]
#[Table(name: 'wedding', schema: 'wedding')]
class Wedding extends AuditingEntity {

  #[Column(name: 'name', type: Types::STRING, length: 100, nullable: false)]
  private string $name;

  #[Column(name: 'on_date', type: Types::DATE_IMMUTABLE, nullable: false)]
  private DateTimeImmutable $onDate;

  #[Column(name: 'budget', type: Types::BIGINT, nullable: false)]
  private int $budget = 0;

  /**
   * @var Collection<int, Guest>
   */
  #[OneToMany(targetEntity: Guest::class, mappedBy: 'wedding', fetch: 'LAZY')]
  private Collection $guests;

  /**
   * @var Collection<int, GuestGroup>
   */
  #[OneToMany(targetEntity: GuestGroup::class, mappedBy: 'wedding', fetch: 'LAZY')]
  private Collection $guestGroups;

  /**
   * @var Collection<int, WeddingUser>
   */
  #[OneToMany(targetEntity: WeddingUser::class, mappedBy: 'wedding', fetch: 'LAZY')]
  private Collection $weddingUsers;

  /**
   * @var Collection<int, WeddingUserInvite>
   */
  #[OneToMany(targetEntity: WeddingUserInvite::class, mappedBy: 'wedding', fetch: 'LAZY')]
  private Collection $weddingUserInvites;

  /**
   * @var Collection<int, WeddingCostEstimate>
   */
  #[OneToMany(targetEntity: WeddingCostEstimate::class, mappedBy: 'wedding', fetch: 'LAZY')]
  private Collection $weddingCostEstimates;

  public function __construct() {
    $this->guests = new ArrayCollection();
    $this->guestGroups = new ArrayCollection();
    $this->weddingUsers = new ArrayCollection();
    $this->weddingUserInvites = new ArrayCollection();
    $this->weddingCostEstimates = new ArrayCollection();
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

  public function getBudget(): Money {
    return Money::PLN($this->budget);
  }

  public function setBudget(Money $money): self {
    $this->budget = (int) $money->getAmount();

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
   * @return Collection<int, GuestGroup>
   */
  public function getGuestGroups(): Collection {
    return $this->guestGroups;
  }

  /**
   * @param Collection<int, GuestGroup> $guestGroups
   */
  public function setGuestGroups(Collection $guestGroups): self {
    $this->guestGroups = $guestGroups;

    return $this;
  }

  public function addGuestGroup(GuestGroup $guestGroup): self {
    if (!$this->guestGroups->contains($guestGroup)) {
      $this->guestGroups->add($guestGroup);
    }

    return $this;
  }

  public function removeGuestGroup(GuestGroup $guestGroup): self {
    $this->guestGroups->removeElement($guestGroup);

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

  /**
   * @return Collection<int, WeddingUserInvite>
   */
  public function getWeddingUserInvites(): Collection {
    return $this->weddingUserInvites;
  }

  /**
   * @param Collection<int, WeddingUserInvite> $weddingUserInvites
   */
  public function setWeddingUserInvites(Collection $weddingUserInvites): self {
    $this->weddingUserInvites = $weddingUserInvites;

    return $this;
  }

  public function addWeddingUserInvite(WeddingUserInvite $weddingUserInvite): self {
    if (!$this->weddingUserInvites->contains($weddingUserInvite)) {
      $this->weddingUserInvites->add($weddingUserInvite);
    }

    return $this;
  }

  public function removeWeddingUserInvite(WeddingUserInvite $weddingUserInvite): self {
    $this->weddingUserInvites->removeElement($weddingUserInvite);

    return $this;
  }

  /**
   * @return Collection<int, WeddingCostEstimate>
   */
  public function getWeddingCostEstimates(): Collection {
    return $this->weddingCostEstimates;
  }

  /**
   * @param Collection<int, WeddingCostEstimate> $weddingCostEstimates
   */
  public function setWeddingCostEstimates(Collection $weddingCostEstimates): self {
    $this->weddingCostEstimates = $weddingCostEstimates;

    return $this;
  }

  public function addWeddingCostEstimate(WeddingCostEstimate $weddingCostEstimate): self {
    if (!$this->weddingCostEstimates->contains($weddingCostEstimate)) {
      $this->weddingCostEstimates->add($weddingCostEstimate);
    }

    return $this;
  }

  public function removeWeddingCostEstimate(WeddingCostEstimate $weddingCostEstimate): self {
    $this->weddingCostEstimates->removeElement($weddingCostEstimate);

    return $this;
  }
}