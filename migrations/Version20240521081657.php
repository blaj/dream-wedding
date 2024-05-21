<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240521081657 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add paid to cost_estimate table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        'ALTER TABLE wedding.cost_estimate ADD COLUMN paid BIGINT NOT NULL DEFAULT 0;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.cost_estimate DROP COLUMN paid;');
  }
}
