<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240509140316 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create cost_estimate_group table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE wedding.cost_estimate_group (
          id SERIAL PRIMARY KEY,
          wedding_id BIGINT NOT NULL,
          name VARCHAR(200) NOT NULL,
          description TEXT,
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
        'GRANT INSERT, SELECT, UPDATE ON wedding.cost_estimate_group TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA wedding TO dream_wedding_app_user;');

    $this->addSql(
        'CREATE INDEX idx_wedding_cost_estimate_group_wedding_id ON wedding.cost_estimate_group(wedding_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_cost_estimate_group_created_by_id ON wedding.cost_estimate_group(created_by_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_cost_estimate_group_updated_by_id ON wedding.cost_estimate_group(updated_by_id);');
    $this->addSql(
        'CREATE INDEX idx_wedding_cost_estimate_group_deleted_by_id ON wedding.cost_estimate_group(deleted_by_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE wedding.cost_estimate_group;');
  }
}
