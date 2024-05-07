<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240507080004 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add order_no to task table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.task ADD COLUMN order_no SMALLINT NOT NULL DEFAULT 0;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.task DROP COLUMN order_no;');
  }
}
