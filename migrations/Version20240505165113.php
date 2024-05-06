<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240505165113 extends AbstractMigration {

  public function getDescription(): string {
    return 'Add wedding and party address';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE wedding.wedding 
              ADD COLUMN wedding_address_city VARCHAR(100), 
              ADD COLUMN wedding_address_street VARCHAR(100), 
              ADD COLUMN wedding_address_postcode CHAR(7), 
              ADD COLUMN party_address_city VARCHAR(100), 
              ADD COLUMN party_address_street VARCHAR(100), 
              ADD COLUMN party_address_postcode CHAR(6);');
  }

  public function down(Schema $schema): void {
    $this->addSql(
        '
            ALTER TABLE wedding.wedding 
              DROP COLUMN wedding_address_city, 
              DROP COLUMN wedding_address_street, 
              DROP COLUMN wedding_address_postcode, 
              DROP COLUMN party_address_city, 
              DROP COLUMN party_address_street, 
              DROP COLUMN party_address_postcode;');

  }
}
