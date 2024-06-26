<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Common\Entity\WeddingContextInterface;
use App\Wedding\Entity\Enum\DietType;
use App\Wedding\Entity\Enum\GuestType;
use App\Wedding\Repository\GuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: GuestRepository::class)]
#[\Doctrine\ORM\Mapping\Table(name: 'guest', schema: 'wedding')]
class Guest extends AuditingEntity implements WeddingContextInterface {

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

  #[ManyToOne(targetEntity: Table::class, fetch: 'LAZY', inversedBy: 'guests')]
  #[JoinColumn(name: 'tables_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  private ?Table $table = null;

  #[Column(name: 'group_order_no', type: Types::SMALLINT, nullable: false, options: ['default' => 0])]
  private int $groupOrderNo = 0;

  #[ManyToOne(targetEntity: GuestGroup::class, fetch: 'LAZY', inversedBy: 'guests')]
  #[JoinColumn(name: 'group_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  private ?GuestGroup $group;

  #[Column(name: 'table_order_no', type: Types::SMALLINT, nullable: false, options: ['default' => 0])]
  private int $tableOrderNo = 0;

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

  public function getTable(): ?Table {
    return $this->table;
  }

  public function setTable(?Table $table): self {
    $this->table = $table;

    return $this;
  }

  public function getGroupOrderNo(): int {
    return $this->groupOrderNo;
  }

  public function setGroupOrderNo(int $groupOrderNo): self {
    $this->groupOrderNo = $groupOrderNo;

    return $this;
  }

  public function getGroup(): ?GuestGroup {
    return $this->group;
  }

  public function setGroup(?GuestGroup $group): self {
    $this->group = $group;

    return $this;
  }

  public function getTableOrderNo(): int {
    return $this->tableOrderNo;
  }

  public function setTableOrderNo(int $tableOrderNo): self {
    $this->tableOrderNo = $tableOrderNo;

    return $this;
  }
}