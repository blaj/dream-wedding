<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Common\Entity\WeddingContextInterface;
use App\Wedding\Repository\TableRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: TableRepository::class)]
#[Table(name: 'task', schema: 'wedding')]
class Task extends AuditingEntity implements WeddingContextInterface {

  #[ManyToOne(targetEntity: Wedding::class, fetch: 'LAZY', inversedBy: 'tasks')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Wedding $wedding;

  #[Column(name: 'name', type: Types::STRING, length: 100, nullable: false)]
  private string $name;

  #[Column(name: 'description', type: Types::TEXT, nullable: true)]
  private ?string $description = null;

  #[Column(name: 'on_date', type: Types::DATETIME_IMMUTABLE, nullable: true)]
  private ?DateTimeImmutable $onDate = null;


  #[ManyToOne(targetEntity: TaskGroup::class, fetch: 'LAZY', inversedBy: 'tasks')]
  #[JoinColumn(name: 'group_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  private ?TaskGroup $group;

  #[Column(name: 'color', type: Types::STRING, length: 20, nullable: true)]
  private ?string $color;

  #[Column(name: 'completed', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
  private bool $completed = false;

  #[Column(name: 'order_no', type: Types::SMALLINT, nullable: false, options: ['default' => 0])]
  private int $orderNo = 0;

  public function getWedding(): Wedding {
    return $this->wedding;
  }

  public function setWedding(Wedding $wedding): self {
    $this->wedding = $wedding;

    return $this;
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

  public function getOnDate(): ?DateTimeImmutable {
    return $this->onDate;
  }

  public function setOnDate(?DateTimeImmutable $onDate): self {
    $this->onDate = $onDate;

    return $this;
  }

  public function getGroup(): ?TaskGroup {
    return $this->group;
  }

  public function setGroup(?TaskGroup $group): self {
    $this->group = $group;

    return $this;
  }

  public function getColor(): ?string {
    return $this->color;
  }

  public function setColor(?string $color): self {
    $this->color = $color;

    return $this;
  }

  public function isCompleted(): bool {
    return $this->completed;
  }

  public function setCompleted(bool $completed): self {
    $this->completed = $completed;

    return $this;
  }

  public function getOrderNo(): int {
    return $this->orderNo;
  }

  public function setOrderNo(int $orderNo): self {
    $this->orderNo = $orderNo;

    return $this;
  }
}