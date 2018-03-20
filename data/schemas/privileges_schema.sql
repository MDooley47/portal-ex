CREATE TABLE privileges (slug TEXT PRIMARY KEY, name TEXT NOT NULL, description TEXT);


INSERT INTO privileges (slug, name, description) VALUES ('anon', 'anonymous', 'Not logged in.');
INSERT INTO privileges (slug, name, description) VALUES ('08hwse', 'authenticated user', 'No special privilege.');
INSERT INTO privileges (slug, name, description) VALUES ('0eIB3s', 'group admin', 'Admin of group, building, or organization.');
INSERT INTO privileges (slug, name, description) VALUES ('HBFE3d', 'system admin', '');