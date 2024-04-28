<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240426230935 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create guests_groups table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE wedding.guests_groups (
          guest_id BIGINT NOT NULL,
          group_id BIGINT NOT NULL,
          
          PRIMARY KEY(guest_id, group_id),
          CONSTRAINT fk_guest_id
            FOREIGN KEY(guest_id)
              REFERENCES wedding.guest(id),
          CONSTRAINT fk_group_id
            FOREIGN KEY(group_id)
              REFERENCES wedding.guest_group(id)
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE, DELETE ON wedding.guests_groups TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA wedding TO dream_wedding_app_user;');

    $this->addSql(
        'CREATE INDEX idx_wedding_guests_groups_guest_id ON wedding.guests_groups(guest_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_guests_groups_group_id ON wedding.guests_groups(group_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE wedding.guests_groups;');
  }
}
