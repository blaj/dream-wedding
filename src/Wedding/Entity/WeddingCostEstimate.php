<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Wedding\Repository\WeddingCostEstimateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Money\Currency;
use Money\Money;

#[Entity(repositoryClass: WeddingCostEstimateRepository::class)]
#[Table(name: 'wedding_cost_estimate', schema: 'wedding')]
class WeddingCostEstimate extends AuditingEntity {

  #[ManyToOne(targetEntity: Wedding::class, fetch: 'LAZY', inversedBy: 'weddingCostEstimates')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Wedding $wedding;

  #[Column(name: 'name', type: Types::STRING, length: 200, nullable: false)]
  private string $name;

  #[Column(name: 'description', type: Types::STRING, nullable: true)]
  private ?string $description = null;

  #[Column(name: 'estimate', type: Types::BIGINT, nullable: false)]
  private int $estimate = 0;

  #[Column(name: 'real', type: Types::BIGINT, nullable: false)]
  private int $real = 0;

  #[Column(name: 'currency', type: Types::STRING, length: 3, nullable: false)]
  private string $currency = 'PLN';

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

  public function getEstimate(): Money {
    return new Money($this->estimate, $this->getCurrency());
  }

  public function setEstimate(Money $money): self {
    $this->estimate = (int) $money->getAmount();

    return $this;
  }

  public function getReal(): Money {
    return new Money($this->real, $this->getCurrency());
  }

  public function setReal(Money $money): self {
    $this->real = (int) $money->getAmount();

    return $this;
  }

  public function getCurrency(): Currency {
    /** @phpstan-ignore-next-line */
    return new Currency($this->currency);
  }

  public function setCurrency(Currency $currency): self {
    $this->currency = $currency->getCode();

    return $this;
  }
}