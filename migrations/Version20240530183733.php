<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240530183733 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add short_content to post.post table';
  }

  public function up(Schema $schema): void {
    $this->addSql('ALTER TABLE post.post ADD COLUMN short_content TEXT NOT NULL;');
  }

  public function down(Schema $schema): void {
    $this->addSql('ALTER TABLE post.post DROP COLUMN short_content;');
  }
}
