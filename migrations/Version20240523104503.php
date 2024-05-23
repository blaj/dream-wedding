<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240523104503 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create offer.category table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE offer.category (
          id SERIAL PRIMARY KEY,
          name VARCHAR(200) NOT NULL,
          description TEXT,
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

    $this->addSql('GRANT INSERT, SELECT, UPDATE ON offer.category TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA offer TO dream_wedding_app_user;');

    $this->addSql('CREATE INDEX idx_offer_category_created_by_id ON offer.category(created_by_id);');
    $this->addSql('CREATE INDEX idx_offer_category_updated_by_id ON offer.category(updated_by_id);');
    $this->addSql('CREATE INDEX idx_offer_category_deleted_by_id ON offer.category(deleted_by_id);');

    $this->addSql(
        '
        CREATE TABLE offer.offers_categories (
          offer_id BIGINT NOT NULL,
          category_id BIGINT NOT NULL,
          
          PRIMARY KEY(offer_id, category_id),
          CONSTRAINT fk_offer_id
            FOREIGN KEY(offer_id)
              REFERENCES offer.offer(id),
          CONSTRAINT fk_category_id
            FOREIGN KEY(category_id)
              REFERENCES offer.category(id)
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE, DELETE ON offer.offers_categories TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA offer TO dream_wedding_app_user;');

    $this->addSql(
        'CREATE INDEX idx_offer_offers_categories_offer_id ON offer.offers_categories(offer_id);');
    $this->addSql(
        'CREATE INDEX idx_offer_offers_categories_category_id ON offer.offers_categories(category_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE offer.offers_categories;');
    $this->addSql('DROP TABLE offer.category;');
  }
}
