CREATE TABLE group (id SERIAL PRIMARY KEY, groupType int NOT NULL, slug text NOT NULL UNIQUE, name text NOT NULL, description text);


INSERT INTO group ("groupType", slug, name, description) VALUES ('1', 'orG332', 'kids', 'example group');
INSERT INTO group ("groupType", slug, name, description) VALUES ('2', '29ry38', 'starks', 'example building');
INSERT INTO group ("groupType", slug, name, description) VALUES ('2', '29ry38', 'snows', 'example building');
INSERT INTO group ("groupType", slug, name, description) VALUES ('3', 'ri12io', 'winterfell', 'example orgnization');
