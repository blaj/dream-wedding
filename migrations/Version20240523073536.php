<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240523073536 extends AbstractMigration
{
  public function getDescription(): string {
    return 'Create post.tag table';
  }

  public function up(Schema $schema): void {
    $this->addSql(
        '
        CREATE TABLE post.tag (
          id SERIAL PRIMARY KEY,
          name VARCHAR(200) NOT NULL,
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

    $this->addSql('GRANT INSERT, SELECT, UPDATE ON post.tag TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA post TO dream_wedding_app_user;');

    $this->addSql('CREATE INDEX idx_post_tag_created_by_id ON post.tag(created_by_id);');
    $this->addSql('CREATE INDEX idx_post_tag_updated_by_id ON post.tag(updated_by_id);');
    $this->addSql('CREATE INDEX idx_post_tag_deleted_by_id ON post.tag(deleted_by_id);');

    $this->addSql(
        '
        CREATE TABLE post.posts_tags (
          post_id BIGINT NOT NULL,
          tag_id BIGINT NOT NULL,
          
          PRIMARY KEY(post_id, tag_id),
          CONSTRAINT fk_post_id
            FOREIGN KEY(post_id)
              REFERENCES post.post(id),
          CONSTRAINT fk_tag_id
            FOREIGN KEY(tag_id)
              REFERENCES post.tag(id)
        );');

    $this->addSql(
        'GRANT INSERT, SELECT, UPDATE, DELETE ON post.posts_tags TO dream_wedding_app_user;');
    $this->addSql(
        'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA post TO dream_wedding_app_user;');

    $this->addSql(
        'CREATE INDEX idx_post_posts_tags_post_id ON post.posts_tags(post_id);');
    $this->addSql(
        'CREATE INDEX idx_post_posts_tags_tag_id ON post.posts_tags(tag_id);');
  }

  public function down(Schema $schema): void {
    $this->addSql('DROP TABLE post.posts_tags;');
    $this->addSql('DROP TABLE post.category;');
  }
}
