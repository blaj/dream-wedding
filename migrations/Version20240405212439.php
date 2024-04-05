<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240405212439 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create wedding schema and table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA wedding;');
    $this->addSql('GRANT USAGE ON SCHEMA wedding TO dream_wedding_app_user;');

    $this->addSql(
        '
        CREATE TABLE wedding.wedding (
          id SERIAL PRIMARY KEY,
          name VARCHAR(100) NOT NULL,
          on_date DATE NOT NULL,
          created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
          created_by_id BIGINT,
          updated_at TIMESTAMP WITHOUT TIME ZONE,
          updated_by_id BIGINT,
          deleted_at TIMESTAMP WITHOUT TIME ZONE,
          deleted_by_id BIGINT,
          deleted BOOLEAN NOT NULL DEFAULT false,
          
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

    $this->addSql('GRANT INSERT, SELECT, UPDATE ON wedding.wedding TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA wedding TO dream_wedding_app_user;');

    $this->addSql('CREATE INDEX idx_wedding_wedding_created_by_id ON wedding.wedding(created_by_id);');
    $this->addSql('CREATE INDEX idx_wedding_wedding_updated_by_id ON wedding.wedding(updated_by_id);');
    $this->addSql('CREATE INDEX idx_wedding_wedding_deleted_by_id ON wedding.wedding(deleted_by_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE wedding.wedding;');
    $this->addSql('DROP SCHEMA wedding;');
  }
}
