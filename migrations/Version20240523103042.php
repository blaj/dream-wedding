<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240523103042 extends AbstractMigration
{
  public function getDescription(): string {
    return 'Create offer schema and table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA offer;');
    $this->addSql('GRANT USAGE ON SCHEMA offer TO dream_wedding_app_user;');

    $this->addSql(
        '
        CREATE TABLE offer.offer (
          id SERIAL PRIMARY KEY,
          title VARCHAR(200) NOT NULL,
          content TEXT NOT NULL,
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

    $this->addSql('GRANT INSERT, SELECT, UPDATE ON offer.offer TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA offer TO dream_wedding_app_user;');

    $this->addSql('CREATE INDEX idx_offer_offer_created_by_id ON offer.offer(created_by_id);');
    $this->addSql('CREATE INDEX idx_offer_offer_updated_by_id ON offer.offer(updated_by_id);');
    $this->addSql('CREATE INDEX idx_offer_offer_deleted_by_id ON offer.offer(deleted_by_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE offer.offer;');
    $this->addSql('DROP SCHEMA offer;');
  }
}
