<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Wedding\Entity\Enum\DietType;
use App\Wedding\Entity\Enum\GuestType;
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

  #[Column(name: 'type', type: Types::STRING, length: 20, nullable: false, enumType: GuestType::class)]
  private GuestType $type = GuestType::GUEST;

  #[Column(name: 'invited', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
  private bool $invited = false;

  #[Column(name: 'confirmed', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
  private bool $confirmed = false;

  #[Column(name: 'accommodation', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
  private bool $accommodation = false;

  #[Column(name: 'transport', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
  private bool $transport = false;

  #[Column(name: 'diet_type', type: Types::STRING, length: 20, nullable: false, enumType: DietType::class)]
  private DietType $dietType = DietType::OMNIVOROUS;

  #[Column(name: 'note', type: Types::TEXT, nullable: true)]
  private ?string $note = null;

  #[Column(name: 'telephone', type: Types::STRING, length: 9, nullable: true)]
  private ?string $telephone = null;

  #[Column(name: 'email', type: Types::STRING, length: 200, nullable: true)]
  private ?string $email = null;

  #[Column(name: 'payment', type: Types::SMALLINT, nullable: false, options: ['default' => 100])]
  private int $payment = 100;

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

  public function getType(): GuestType {
    return $this->type;
  }

  public function setType(GuestType $type): self {
    $this->type = $type;

    return $this;
  }

  public function isInvited(): bool {
    return $this->invited;
  }

  public function setInvited(bool $invited): self {
    $this->invited = $invited;

    return $this;
  }

  public function isConfirmed(): bool {
    return $this->confirmed;
  }

  public function setConfirmed(bool $confirmed): self {
    $this->confirmed = $confirmed;

    return $this;
  }

  public function isAccommodation(): bool {
    return $this->accommodation;
  }

  public function setAccommodation(bool $accommodation): self {
    $this->accommodation = $accommodation;

    return $this;
  }

  public function isTransport(): bool {
    return $this->transport;
  }

  public function setTransport(bool $transport): self {
    $this->transport = $transport;

    return $this;
  }

  public function getDietType(): DietType {
    return $this->dietType;
  }

  public function setDietType(DietType $dietType): self {
    $this->dietType = $dietType;

    return $this;
  }

  public function getNote(): ?string {
    return $this->note;
  }

  public function setNote(?string $note): self {
    $this->note = $note;

    return $this;
  }

  public function getTelephone(): ?string {
    return $this->telephone;
  }

  public function setTelephone(?string $telephone): self {
    $this->telephone = $telephone;

    return $this;
  }

  public function getEmail(): ?string {
    return $this->email;
  }

  public function setEmail(?string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getPayment(): int {
    return $this->payment;
  }

  public function setPayment(int $payment): self {
    $this->payment = $payment;

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