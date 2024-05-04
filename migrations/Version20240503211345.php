<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240503211345 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add group_id to task table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.task ADD COLUMN group_id BIGINT;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.task DROP COLUMN group_id;');
  }
}
