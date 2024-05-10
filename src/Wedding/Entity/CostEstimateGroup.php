<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Common\Entity\WeddingContextInterface;
use App\Wedding\Repository\CostEstimateGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: CostEstimateGroupRepository::class)]
#[Table(name: 'cost_estimate_group', schema: 'wedding')]
class CostEstimateGroup extends AuditingEntity implements WeddingContextInterface {

  #[Column(name: 'name', type: Types::STRING, length: 200, nullable: false)]
  private string $name;

  #[Column(name: 'description', type: Types::TEXT, nullable: true)]
  private ?string $description = null;

  #[ManyToOne(targetEntity: Wedding::class, fetch: 'LAZY', inversedBy: 'guestGroups')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Wedding $wedding;

  /**
   * @var Collection<int, WeddingCostEstimate>
   */
  #[OneToMany(targetEntity: WeddingCostEstimate::class, mappedBy: 'group', fetch: 'LAZY')]
  private Collection $costEstimates;

  public function __construct() {
    $this->costEstimates = new ArrayCollection();
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
   * @return Collection<int, WeddingCostEstimate>
   */
  public function getCostEstimates(): Collection {
    return $this->costEstimates;
  }

  /**
   * @param Collection<int, WeddingCostEstimate> $costEstimates
   */
  public function setCostEstimates(Collection $costEstimates): self {
    $this->costEstimates = $costEstimates;

    return $this;
  }

  public function addCostEstimate(WeddingCostEstimate $costEstimate): self {
    if (!$this->costEstimates->contains($costEstimate)) {
      $costEstimate->setGroup($this);
      $this->costEstimates->add($costEstimate);
    }

    return $this;
  }

  public function removeCostEstimate(WeddingCostEstimate $costEstimate): self {
    $costEstimate->setGroup(null);
    $this->costEstimates->removeElement($costEstimate);

    return $this;
  }
}