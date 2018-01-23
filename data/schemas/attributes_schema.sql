CREATE TABLE "attributes" (id SERIAL PRIMARY KEY, slug text NOT NULL UNIQUE, name text, description text, data text NOT NULL);
