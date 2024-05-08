<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240507203017 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add order_no to guest table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.guest ADD COLUMN order_no SMALLINT NOT NULL DEFAULT 0;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.guest DROP COLUMN order_no;');
  }
}
