-- 1. Create database and users
CREATE DATABASE dream_wedding;

CREATE USER dream_wedding_app_user WITH PASSWORD 'Dr3@mW3dd!ng' NOINHERIT;
COMMENT ON ROLE dream_wedding_app_user IS 'Backend app user';

CREATE USER dream_wedding_migrations_user WITH PASSWORD 'Dr3@mW3dd!ngMigr@ti0ns' NOINHERIT;
COMMENT ON ROLE dream_wedding_migrations_user IS 'Doctrine migrations user';

GRANT CREATE ON DATABASE dream_wedding TO dream_wedding_migrations_user;

\c dream_wedding;
GRANT USAGE, CREATE ON SCHEMA public TO dream_wedding_migrations_user;

-- 2. Grant delete privilege for all tables for fixtures
DO
$$
DECLARE schemaname text;
BEGIN
FOR schemaname IN (SELECT nspname FROM pg_namespace) LOOP
        EXECUTE 'GRANT DELETE ON ALL TABLES IN SCHEMA ' || schemaname || ' TO dream_wedding_app_user';
EXECUTE 'GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA ' || schemaname || ' TO dream_wedding_app_user';
END LOOP;
END
$$;