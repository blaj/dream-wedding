<?php

namespace App\FileStorage\Entity;

use App\Common\Entity\AuditingEntity;
use App\FileStorage\Repository\LocalFileResourceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: LocalFileResourceRepository::class)]
#[Table(name: 'local_file_resource', schema: 'file_resource')]
class LocalFileResource extends AuditingEntity {

  #[Column(name: 'content_type', type: Types::STRING, nullable: false)]
  private string $contentType;

  #[Column(name: 'path', type: Types::STRING, nullable: false)]
  private string $path;

  #[Column(name: 'original_file_name', type: Types::STRING, nullable: false)]
  private string $originalFileName;

  #[Column(name: 'size', type: Types::INTEGER, nullable: false)]
  private int $size;

  public function getContentType(): string {
    return $this->contentType;
  }

  public function setContentType(string $contentType): self {
    $this->contentType = $contentType;

    return $this;
  }

  public function getPath(): string {
    return $this->path;
  }

  public function setPath(string $path): self {
    $this->path = $path;

    return $this;
  }

  public function getOriginalFileName(): string {
    return $this->originalFileName;
  }

  public function setOriginalFileName(string $originalFileName): self {
    $this->originalFileName = $originalFileName;

    return $this;
  }

  public function getSize(): int {
    return $this->size;
  }

  public function setSize(int $size): self {
    $this->size = $size;

    return $this;
  }
}