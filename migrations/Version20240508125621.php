<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240508125621 extends AbstractMigration {

  public function getDescription(): string {
    return 'Rename order_no to group_order_no in guest table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.guest RENAME COLUMN order_no TO group_order_no');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.guest RENAME COLUMN group_order_no TO order_no;');
  }
}
