CREATE DATABASE dream_wedding;

CREATE USER dream_wedding_app_user WITH PASSWORD 'Dr3@mW3dd!ng' NOINHERIT;
COMMENT ON ROLE dream_wedding_app_user IS 'Backend app user';

CREATE USER dream_wedding_migrations_user WITH PASSWORD 'Dr3@mW3dd!ngMigr@ti0ns' NOINHERIT;
COMMENT ON ROLE dream_wedding_migrations_user IS 'Doctrine migrations user';

GRANT CREATE ON DATABASE dream_wedding TO dream_wedding_migrations_user;
GRANT CREATE ON SCHEMA public TO dream_wedding_migrations_user;