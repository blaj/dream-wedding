<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240529064022 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create file_resource schema and local_file_resource table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA file_resource;');
    $this->addSql('GRANT USAGE ON SCHEMA file_resource TO dream_wedding_app_user;');

    $this->addSql(
        '
        CREATE TABLE file_resource.local_file_resource (
          id SERIAL PRIMARY KEY, 
          content_type VARCHAR(100) NOT NULL, 
          path TEXT NOT NULL,
          original_file_name TEXT NOT NULL,
          size BIGINT NOT NULL,
          deleted BOOLEAN NOT NULL DEFAULT false
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE ON file_resource.local_file_resource TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA file_resource TO dream_wedding_app_user;');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE file_resource.local_file_resource;');
    $this->addSql('DROP SCHEMA file_resource;');
  }
}
