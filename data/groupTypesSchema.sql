CREATE TABLE "groupTypes" (id SERIAL PRIMARY KEY, slug text NOT NULL UNIQUE, name text NOT NULL, description text NOT NULL);


INSERT INTO "groupTypes" (name, slug, description) VALUES ('group', 'orG332', 'A group of users within the system, an organization or building.');
INSERT INTO "groupTypes" (name, slug, description) VALUES ('building', '29ry38', 'A physical or virtual location belonging to an organization.');
INSERT INTO "groupTypes" (name, slug, description) VALUES ('organization', 'ri12io', 'The highest distinct entity.');
