<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240510082131 extends AbstractMigration {

  public function getDescription(): string {
    return 'Rename table from wedding_cost_estimate to cost_estimate';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.wedding_cost_estimate RENAME TO cost_estimate;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE wedding.cost_estimate RENAME TO wedding_cost_estimate;');
  }
}
