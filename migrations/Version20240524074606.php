<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240524074606 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add language to user table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        'ALTER TABLE users.users ADD COLUMN language CHAR(2) NOT NULL DEFAULT \'pl\';');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE users.users DROP COLUMN language;');
  }
}
