<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240424084628 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create messenger schema and messages table';
  }

  public function up(Schema $schema): void {
    $this->addSql('CREATE SCHEMA messenger;');
    $this->addSql('GRANT USAGE ON SCHEMA messenger TO dream_wedding_app_user;');

    $this->addSql(
        '
        CREATE TABLE messenger.messages (
          id SERIAL PRIMARY KEY,
          body TEXT NOT NULL,
          headers TEXT NOT NULL,
          queue_name VARCHAR(190) NOT NULL,
          created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
          available_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
          delivered_at TIMESTAMP WITHOUT TIME ZONE
        );');

    $this->addSql('GRANT INSERT, SELECT, UPDATE, DELETE ON messenger.messages TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA messenger TO dream_wedding_app_user;');

    $this->addSql('CREATE INDEX idx_messenger_messages_queue_name ON messenger.messages(queue_name);');
    $this->addSql('CREATE INDEX idx_messenger_messages_available_at ON messenger.messages(available_at);');
    $this->addSql('CREATE INDEX idx_messenger_messages_delivered_at ON messenger.messages(delivered_at);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE messenger.messages;');
    $this->addSql('DROP SCHEMA messenger;');
  }
}
