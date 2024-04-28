<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240428153235 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add tags to guest table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE wedding.guest 
              ADD COLUMN type VARCHAR(20) NOT NULL DEFAULT \'GUEST\', 
              ADD COLUMN invited BOOLEAN NOT NULL DEFAULT false, 
              ADD COLUMN confirmed BOOLEAN NOT NULL DEFAULT false, 
              ADD COLUMN accommodation BOOLEAN NOT NULL DEFAULT false, 
              ADD COLUMN transport BOOLEAN NOT NULL DEFAULT false, 
              ADD COLUMN diet_type VARCHAR(20) NOT NULL DEFAULT \'OMNIVOROUS\', 
              ADD COLUMN note TEXT, 
              ADD COLUMN telephone CHAR(9), 
              ADD COLUMN email VARCHAR(200), 
              ADD COLUMN payment SMALLINT NOT NULL DEFAULT 100;');
  }

  public function down(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE wedding.guest 
              DROP COLUMN type, 
              DROP COLUMN invited, 
              DROP COLUMN confirmed, 
              DROP COLUMN accommodation, 
              DROP COLUMN transport, 
              DROP COLUMN diet_type, 
              DROP COLUMN note, 
              DROP COLUMN telephone, 
              DROP COLUMN email, 
              DROP COLUMN payment;');
  }
}
