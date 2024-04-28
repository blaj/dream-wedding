<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Wedding\Repository\GuestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: GuestRepository::class)]
#[Table(name: 'guest', schema: 'wedding')]
class Guest extends AuditingEntity {

  #[Column(name: 'first_name', type: Types::STRING, length: 100, nullable: false)]
  private string $firstName;

  #[Column(name: 'last_name', type: Types::STRING, length: 100, nullable: false)]
  private string $lastName;

  #[ManyToOne(targetEntity: Wedding::class, fetch: 'LAZY', inversedBy: 'guests')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Wedding $wedding;

  /**
   * @var Collection<int, GuestContact>
   */
  #[OneToMany(targetEntity: GuestContact::class, mappedBy: 'guest', fetch: 'LAZY')]
  private Collection $contacts;

  /**
   * @var Collection<int, GuestGroup>
   */
  #[ManyToMany(targetEntity: GuestGroup::class, inversedBy: 'guests', fetch: 'LAZY')]
  #[JoinTable(name: 'guests_groups', schema: 'wedding')]
  private Collection $groups;

  public function __construct() {
    $this->contacts = new ArrayCollection();
    $this->groups = new ArrayCollection();
  }

  public function getFirstName(): string {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): string {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self {
    $this->lastName = $lastName;

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
   * @return Collection<int, GuestContact>
   */
  public function getContacts(): Collection {
    return $this->contacts;
  }

  /**
   * @param Collection<int, GuestContact> $contacts
   */
  public function setContacts(Collection $contacts): self {
    $this->contacts = $contacts;

    return $this;
  }

  public function addContact(GuestContact $contact): self {
    if (!$this->contacts->contains($contact)) {
      $this->contacts->add($contact);
    }

    return $this;
  }

  public function removeContact(GuestContact $contact): self {
    $this->contacts->removeElement($contact);

    return $this;
  }

  /**
   * @return Collection<int, GuestGroup>
   */
  public function getGroups(): Collection {
    return $this->groups;
  }

  /**
   * @param Collection<int, GuestGroup> $groups
   */
  public function setGroups(Collection $groups): self {
    $this->groups = $groups;

    return $this;
  }

  public function addGroup(GuestGroup $group): self {
    if (!$this->groups->contains($group)) {
      $this->groups->add($group);
    }

    return $this;
  }

  public function removeGroup(GuestGroup $group): self {
    $this->groups->removeElement($group);

    return $this;
  }
}