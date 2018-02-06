CREATE TABLE "groupTypes" (id SERIAL PRIMARY KEY, slug text NOT NULL UNIQUE, name text NOT NULL, description text);


INSERT INTO "groupTypes" (slug, name, description) VALUES ('orG332', 'group', 'A group of users within the system, an organization or building.');
INSERT INTO "groupTypes" (slug, name, description) VALUES ('29ry38', 'building', 'A physical or virtual location belonging to an organization.');
INSERT INTO "groupTypes" (slug, name, description) VALUES ('ri12io', 'organization', 'The highest distinct entity.');
