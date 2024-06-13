<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240613115202 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add short_content to offer.offer table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE offer.offer ADD COLUMN short_content TEXT NOT NULL;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE offer.offer DROP COLUMN short_content;');
  }
}
