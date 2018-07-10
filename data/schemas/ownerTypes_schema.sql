CREATE TABLE "ownerTypes" (slug TEXT PRIMARY KEY, name TEXT NOT NULL UNIQUE, description TEXT);


INSERT INTO "ownerTypes" (slug, name, description) VALUES ('orG332', 'user', 'A user in the system.');
INSERT INTO "ownerTypes" (slug, name, description) VALUES ('29ry38', 'group', 'A group of any type.');
