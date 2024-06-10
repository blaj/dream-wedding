<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240610122324 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add auditing fields to local_file_resource table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE file_resource.local_file_resource 
              ADD COLUMN created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(), 
              ADD COLUMN created_by_id BIGINT,
              ADD COLUMN updated_at TIMESTAMP WITHOUT TIME ZONE,
              ADD COLUMN updated_by_id BIGINT,
              ADD COLUMN deleted_at TIMESTAMP WITHOUT TIME ZONE,
              ADD COLUMN deleted_by_id BIGINT,
              ADD CONSTRAINT fk_created_by_id FOREIGN KEY(created_by_id) REFERENCES users.users(id),
              ADD CONSTRAINT fk_updated_by_id FOREIGN KEY(updated_by_id) REFERENCES users.users(id),
              ADD CONSTRAINT fk_deleted_by_id FOREIGN KEY(deleted_by_id) REFERENCES users.users(id);');

    $this->addSql(
        'CREATE INDEX idx_file_resource_local_file_resource_created_by_id ON file_resource.local_file_resource(created_by_id);');
    $this->addSql(
        'CREATE INDEX idx_file_resource_local_file_resource_updated_by_id ON file_resource.local_file_resource(updated_by_id);');
    $this->addSql(
        'CREATE INDEX idx_file_resource_local_file_resource_deleted_by_id ON file_resource.local_file_resource(deleted_by_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE file_resource.local_file_resource 
              DROP COLUMN created_at, 
              DROP COLUMN created_by_id,
              DROP COLUMN updated_at, 
              DROP COLUMN updated_by_id, 
              DROP COLUMN deleted_at,
              DROP COLUMN deleted_by_id;');
  }
}
