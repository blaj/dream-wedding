<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Common\Entity\WeddingContextInterface;
use App\Wedding\Repository\TaskGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: TaskGroupRepository::class)]
#[Table(name: 'task_group', schema: 'wedding')]
class TaskGroup extends AuditingEntity implements WeddingContextInterface {

  #[ManyToOne(targetEntity: Wedding::class, fetch: 'LAZY', inversedBy: 'taskGroups')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Wedding $wedding;

  #[Column(name: 'name', type: Types::STRING, length: 100, nullable: false)]
  private string $name;

  #[Column(name: 'color', type: Types::STRING, length: 20, nullable: true)]
  private ?string $color = null;

  /**
   * @var Collection<int, Task>
   */
  #[OneToMany(targetEntity: Task::class, mappedBy: 'group', fetch: 'LAZY')]
  private Collection $tasks;

  public function __construct() {
    $this->tasks = new ArrayCollection();
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

  public function getColor(): ?string {
    return $this->color;
  }

  public function setColor(?string $color): self {
    $this->color = $color;

    return $this;
  }

  /**
   * @return Collection<int, Task>
   */
  public function getTasks(): Collection {
    return $this->tasks;
  }

  /**
   * @param Collection<int, Task> $tasks
   */
  public function setTasks(Collection $tasks): self {
    $this->tasks = $tasks;

    return $this;
  }

  public function addTask(Task $task): self {
    if (!$this->tasks->contains($task)) {
      $task->setGroup($this);
      $this->tasks->add($task);
    }

    return $this;
  }

  public function removeTask(Task $task): self {
    $task->setGroup(null);
    $this->tasks->removeElement($task);

    return $this;
  }
}