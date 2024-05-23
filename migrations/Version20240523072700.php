<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240523072700 extends AbstractMigration {

  public function getDescription(): string {
    return 'Create post.category table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE post.category (
          id SERIAL PRIMARY KEY,
          name VARCHAR(200) NOT NULL,
          description TEXT,
          created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT NOW(),
          created_by_id BIGINT,
          updated_at TIMESTAMP WITHOUT TIME ZONE,
          updated_by_id BIGINT,
          deleted_at TIMESTAMP WITHOUT TIME ZONE,
          deleted_by_id BIGINT,
          deleted BOOLEAN NOT NULL DEFAULT false,
          
          CONSTRAINT fk_created_by_id
            FOREIGN KEY(created_by_id)
              REFERENCES users.users(id),
          CONSTRAINT fk_updated_by_id
            FOREIGN KEY(updated_by_id)
              REFERENCES users.users(id),
          CONSTRAINT fk_deleted_by_id
            FOREIGN KEY(deleted_by_id)
              REFERENCES users.users(id)
        );');

    $this->addSql('GRANT INSERT, SELECT, UPDATE ON post.category TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA post TO dream_wedding_app_user;');

    $this->addSql('CREATE INDEX idx_post_category_created_by_id ON post.category(created_by_id);');
    $this->addSql('CREATE INDEX idx_post_category_updated_by_id ON post.category(updated_by_id);');
    $this->addSql('CREATE INDEX idx_post_category_deleted_by_id ON post.category(deleted_by_id);');

    $this->addSql(
        '
        CREATE TABLE post.posts_categories (
          post_id BIGINT NOT NULL,
          category_id BIGINT NOT NULL,
          
          PRIMARY KEY(post_id, category_id),
          CONSTRAINT fk_post_id
            FOREIGN KEY(post_id)
              REFERENCES post.post(id),
          CONSTRAINT fk_category_id
            FOREIGN KEY(category_id)
              REFERENCES post.category(id)
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE, DELETE ON post.posts_categories TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA post TO dream_wedding_app_user;');

    $this->addSql(
        'CREATE INDEX idx_post_posts_categories_post_id ON post.posts_categories(post_id);');
    $this->addSql(
        'CREATE INDEX idx_post_posts_categories_category_id ON post.posts_categories(category_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE post.posts_categories;');
    $this->addSql('DROP TABLE post.category;');
  }
}
