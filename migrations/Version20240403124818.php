<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240403124818 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create users schema and table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA users;');
    $this->addSql('GRANT USAGE ON SCHEMA users TO dream_wedding_app_user;');

    $this->addSql(
        '
        CREATE TABLE users.users (
          id SERIAL PRIMARY KEY,
          username VARCHAR(50) NOT NULL,
          password VARCHAR(200) NOT NULL,
          email VARCHAR(200) NOT NULL,
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

    $this->addSql('GRANT INSERT, SELECT, UPDATE ON users.users TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA users TO dream_wedding_app_user;');

    $this->addSql('CREATE INDEX idx_users_users_created_by_id ON users.users(created_by_id);');
    $this->addSql('CREATE INDEX idx_users_users_updated_by_id ON users.users(updated_by_id);');
    $this->addSql('CREATE INDEX idx_users_users_deleted_by_id ON users.users(deleted_by_id);');

    // admin/admin
    $this->addSql(
        'INSERT INTO users.users(username, password, email) VALUES (\'admin\', \'$2y$13$OeDp.7ou0ncKa/5wpEAzwO/I5Hk7hK83mXu9ySvPyHOfBI73wWEiq\', \'admin@dream-wedding.com\')');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE users.users;');
    $this->addSql('DROP SCHEMA users;');
  }
}
