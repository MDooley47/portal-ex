CREATE TABLE privileges (id SERIAL PRIMARY KEY, slug text NOT NULL UNIQUE, name text NOT NULL, description text);


INSERT INTO privileges (slug, name, description) VALUES ('orG332', 'anonymous', 'Not logged in.');
INSERT INTO privileges (slug, name, description) VALUES ('29ry38', 'authenticated user', 'No special privilege.');
INSERT INTO privileges (slug, name, description) VALUES ('ri12io', 'group admin', 'Admin of group, building, or organization.');
INSERT INTO privileges (slug, name, description) VALUES ('oq34fE', 'system admin', '');
