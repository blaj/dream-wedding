<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240405220004 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create guest_contact table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE wedding.guest_contact (
          id SERIAL PRIMARY KEY,
          value VARCHAR(200) NOT NULL,
          type VARCHAR(20) NOT NULL,
          guest_id BIGINT NOT NULL,
          created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
          created_by_id BIGINT,
          updated_at TIMESTAMP WITHOUT TIME ZONE,
          updated_by_id BIGINT,
          deleted_at TIMESTAMP WITHOUT TIME ZONE,
          deleted_by_id BIGINT,
          deleted BOOLEAN NOT NULL DEFAULT false,
          
          CONSTRAINT fk_guest_id
            FOREIGN KEY(guest_id)
              REFERENCES wedding.guest(id),
          CONSTRAINT fk_created_by_id
            FOREIGN KEY(created_by_id)
              REFERENCES users.users(id),
          CONSTRAINT fk_updated_by_id
            FOREIGN KEY(updated_by_id)
              REFERENCES users.users(id),
          CONSTRAINT fk_deleted_by_id
            FOREIGN KEY(deleted_by_id)
              REFERENCES users.users(id)
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE ON wedding.guest_contact TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA wedding TO dream_wedding_app_user;');

    $this->addSql('CREATE INDEX idx_wedding_guest_guest_id ON wedding.guest_contact(guest_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_guest_contact_created_by_id ON wedding.guest_contact(created_by_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_guest_contact_updated_by_id ON wedding.guest_contact(updated_by_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_guest_contact_deleted_by_id ON wedding.guest_contact(deleted_by_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE wedding.guest_contact;');
  }
}
