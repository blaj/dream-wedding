<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240429223233 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add tables_id to guest table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.guest ADD COLUMN tables_id BIGINT;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.guest DROP COLUMN tables_id;');
  }
}
