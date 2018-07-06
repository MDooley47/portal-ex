CREATE TABLE "groupTypes" (slug TEXT PRIMARY KEY, name TEXT NOT NULL, level INT NOT NULL, description TEXT);


INSERT INTO "groupTypes" (slug, name, level, description) VALUES ('orG332', 'group', 2, 'A group of users within the system, an organization or building.');
INSERT INTO "groupTypes" (slug, name, level, description) VALUES ('29ry38', 'building', 1, 'A physical or virtual location belonging to an organization.');
INSERT INTO "groupTypes" (slug, name, level, description) VALUES ('ri12io', 'organization', 0, 'The highest distinct entity.');
