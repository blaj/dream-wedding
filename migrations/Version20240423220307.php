<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240423220307 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create wedding_user_invite table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE wedding.wedding_user_invite (
          id SERIAL PRIMARY KEY,
          wedding_id BIGINT NOT NULL,
          user_email VARCHAR(200) NOT NULL,
          token CHAR(60),
          role VARCHAR(20) NOT NULL,
          created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
          created_by_id BIGINT,
          updated_at TIMESTAMP WITHOUT TIME ZONE,
          updated_by_id BIGINT,
          deleted_at TIMESTAMP WITHOUT TIME ZONE,
          deleted_by_id BIGINT,
          deleted BOOLEAN NOT NULL DEFAULT false,
          
          CONSTRAINT fk_wedding_id
            FOREIGN KEY(wedding_id)
              REFERENCES wedding.wedding(id),
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
        'GRANT INSERT, SELECT, UPDATE ON wedding.wedding_user_invite TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA wedding TO dream_wedding_app_user;');

    $this->addSql(
        'CREATE INDEX idx_wedding_wedding_user_invite_wedding_id ON wedding.wedding_user_invite(wedding_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_wedding_user_invite_created_by_id ON wedding.wedding_user_invite(created_by_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_wedding_user_invite_updated_by_id ON wedding.wedding_user_invite(updated_by_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_wedding_user_invite_deleted_by_id ON wedding.wedding_user_invite(deleted_by_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE wedding.wedding_user_invite;');
  }
}
