<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240429141632 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add budget to wedding table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.wedding ADD COLUMN budget BIGINT NOT NULL DEFAULT 0;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.wedding DROP COLUMN budget;');
  }
}
