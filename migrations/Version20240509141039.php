<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240509141039 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add group_id to guest table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE wedding.wedding_cost_estimate 
                ADD COLUMN group_id BIGINT, 
                ADD CONSTRAINT fk_group_id FOREIGN KEY(group_id) REFERENCES wedding.cost_estimate_group(id);');

    $this->addSql(
        'CREATE INDEX idx_wedding_wedding_cost_estimate_group_id ON wedding.wedding_cost_estimate(group_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.wedding_cost_estimate DROP COLUMN group_id;');
  }
}
