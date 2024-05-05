<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240504191130 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add color to task and task_group';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.task ADD COLUMN color VARCHAR(20);');
    $this->addSql('ALTER TABLE wedding.task_group ADD COLUMN color VARCHAR(20);');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.task DROP COLUMN color;');
    $this->addSql('ALTER TABLE wedding.task_group DROP COLUMN color;');
  }
}
