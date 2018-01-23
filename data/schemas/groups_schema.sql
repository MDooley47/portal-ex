CREATE TABLE groups (id SERIAL PRIMARY KEY, "groupType" int NOT NULL, slug text NOT NULL UNIQUE, name text NOT NULL, description text);


INSERT INTO groups ("groupType", slug, name, description) VALUES ('1', 'orG332', 'kids', 'example group');
INSERT INTO groups ("groupType", slug, name, description) VALUES ('2', '29ry38', 'starks', 'example building');
INSERT INTO groups ("groupType", slug, name, description) VALUES ('2', '29rsdf', 'snows', 'example building');
INSERT INTO groups ("groupType", slug, name, description) VALUES ('3', 'ri12io', 'winterfell', 'example orgnization');
