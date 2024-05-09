<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Common\Entity\WeddingContextInterface;
use App\Wedding\Entity\Enum\TableType;
use App\Wedding\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: TableRepository::class)]
#[\Doctrine\ORM\Mapping\Table(name: 'tables', schema: 'wedding')]
class Table extends AuditingEntity implements WeddingContextInterface {

  #[ManyToOne(targetEntity: Wedding::class, fetch: 'LAZY', inversedBy: 'guests')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Wedding $wedding;

  #[Column(name: 'name', type: Types::STRING, length: 100, nullable: false)]
  private string $name;

  #[Column(name: 'description', type: Types::TEXT, nullable: true)]
  private ?string $description = null;

  #[Column(name: 'type', type: Types::STRING, length: 20, nullable: false, enumType: TableType::class)]
  private TableType $type = TableType::SQUARE;

  #[Column(name: 'number_of_seats', type: Types::SMALLINT, nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private int $numberOfSeats = 1;

  /**
   * @var Collection<int, Guest>
   */
  #[OneToMany(targetEntity: Guest::class, mappedBy: 'table', fetch: 'LAZY')]
  private Collection $guests;

  public function __construct() {
    $this->guests = new ArrayCollection();
  }

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

  public function getType(): TableType {
    return $this->type;
  }

  public function setType(TableType $type): self {
    $this->type = $type;

    return $this;
  }

  public function getNumberOfSeats(): int {
    return $this->numberOfSeats;
  }

  public function setNumberOfSeats(int $numberOfSeats): self {
    $this->numberOfSeats = $numberOfSeats;

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
  public function setGuests(Collection $guests): void {
    $this->guests = $guests;
  }

  public function addGuest(Guest $guest): self {
    if (!$this->guests->contains($guest)) {
      $guest->setTable($this);
      $this->guests->add($guest);
    }

    return $this;
  }

  public function removeGuest(Guest $guest): self {
    $guest->setTable(null);
    $this->guests->removeElement($guest);

    return $this;
  }
}