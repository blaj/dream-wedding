<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240508061418 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add group_id to guest table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE wedding.guest 
                ADD COLUMN group_id BIGINT, 
                ADD CONSTRAINT fk_group_id FOREIGN KEY(group_id) REFERENCES wedding.guest_group(id);');

    $this->addSql(
        'CREATE INDEX idx_wedding_guest_group_id ON wedding.guest(group_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.guest DROP COLUMN group_id;');
  }
}
