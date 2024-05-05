<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240505064815 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add completed to task table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.task ADD COLUMN completed BOOLEAN NOT NULL DEFAULT false;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.task DROP COLUMN completed;');
  }
}
