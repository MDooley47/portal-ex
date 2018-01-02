CREATE TABLE group (id SERIAL PRIMARY KEY, slug text NOT NULL UNIQUE, name text NOT NULL, description text);


INSERT INTO group (slug, name, description) VALUES ('orG332', 'kids', 'example tab');
INSERT INTO group (slug, name, description) VALUES ('29ry38', 'starks', 'example tab');
INSERT INTO group (slug, name, description) VALUES ('29ry38', 'snows', 'example tab');
INSERT INTO group (slug, name, description) VALUES ('ri12io', 'winterfell', 'example tab');
