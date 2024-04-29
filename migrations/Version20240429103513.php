<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240429103513 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add quantity, unit type and depends_on_guests to wedding_cost_estimate table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE wedding.wedding_cost_estimate 
              ADD COLUMN quantity INTEGER NOT NULL DEFAULT 1, 
              ADD COLUMN unit_type VARCHAR(20) NOT NULL DEFAULT \'PIECE\', 
              ADD COLUMN depends_on_guests BOOLEAN NOT NULL DEFAULT false;');
  }

  public function down(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE wedding.wedding_cost_estimate 
              DROP COLUMN quantity, 
              DROP COLUMN unit_type, 
              DROP COLUMN depends_on_guests;');
  }
}
