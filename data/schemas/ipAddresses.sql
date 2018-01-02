CREATE TABLE "ipAddresses" (id SERIAL PRIMARY KEY, slug text NOT NULL UNIQUE, name text, description text, ip INET NOT NULL);
