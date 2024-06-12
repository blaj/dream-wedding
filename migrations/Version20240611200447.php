<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240611200447 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add heading_image_id to offer.offer table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE offer.offer 
              ADD COLUMN heading_image_id BIGINT, 
              ADD CONSTRAINT fk_heading_image_id FOREIGN KEY(heading_image_id) REFERENCES file_resource.local_file_resource(id);');
    $this->addSql(
        'CREATE INDEX idx_offer_offer_heading_image_id ON offer.offer(heading_image_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE offer.offer DROP COLUMN heading_image_id;');
  }
}
