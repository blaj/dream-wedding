<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240521081200 extends AbstractMigration {

  public function getDescription(): string {
    return 'Rename column names in cost_estimate';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.cost_estimate RENAME COLUMN estimate TO cost;');
    $this->addSql('ALTER TABLE wedding.cost_estimate RENAME COLUMN real TO advance_payment;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.cost_estimate RENAME COLUMN cost TO estimate;');
    $this->addSql('ALTER TABLE wedding.cost_estimate RENAME COLUMN advance_payment TO real;');
  }
}
