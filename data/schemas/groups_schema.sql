CREATE TABLE groups (slug TEXT PRIMARY KEY, "groupType" TEXT NOT NULL, name TEXT NOT NULL, description TEXT);


INSERT INTO groups (slug, "groupType", name, description) VALUES ('orG332', 'orG332', 'kids', 'example group');
INSERT INTO groups ("groupType", slug, name, description) VALUES ('29ry38', '29ry38', 'starks', 'example building');
INSERT INTO groups ("groupType", slug, name, description) VALUES ('29ry38', '29rsdf', 'snows', 'example building');
INSERT INTO groups ("groupType", slug, name, description) VALUES ('ri12io', 'ri12io', 'winterfell', 'example orgnization');
