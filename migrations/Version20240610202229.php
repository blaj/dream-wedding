<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240610202229 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add role to users table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE users.users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT \'USER\';');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE users.users DROP COLUMN role;');
  }
}
