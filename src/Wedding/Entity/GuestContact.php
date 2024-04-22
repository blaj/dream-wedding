<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Wedding\Entity\Enum\ContactType;
use App\Wedding\Repository\GuestContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: GuestContactRepository::class)]
#[Table(name: 'wedding', schema: 'guest_contact')]
class GuestContact extends AuditingEntity {

  #[Column(name: 'first_name', type: Types::STRING, length: 200, nullable: false)]
  private string $value;

  #[Column(name: 'type', type: Types::STRING, length: 20, nullable: false, enumType: ContactType::class)]
  private ContactType $type;

  #[ManyToOne(targetEntity: Guest::class, fetch: 'LAZY', inversedBy: 'contacts')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Guest $guest;

  public function getValue(): string {
    return $this->value;
  }

  public function setValue(string $value): self {
    $this->value = $value;

    return $this;
  }

  public function getType(): ContactType {
    return $this->type;
  }

  public function setType(ContactType $type): self {
    $this->type = $type;

    return $this;
  }

  public function getGuest(): Guest {
    return $this->guest;
  }

  public function setGuest(Guest $guest): self {
    $this->guest = $guest;

    return $this;
  }
}