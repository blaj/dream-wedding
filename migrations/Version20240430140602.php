<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240430140602 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add number_of_seats to tables table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        'ALTER TABLE wedding.tables ADD COLUMN number_of_seats SMALLINT NOT NULL DEFAULT 1;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.tables DROP COLUMN number_of_seats;');
  }
}
