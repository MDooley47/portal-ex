CREATE TABLE privileges (slug TEXT PRIMARY KEY, name TEXT NOT NULL, level INT NOT NULL, description TEXT);


INSERT INTO privileges (slug, name, level, description) VALUES ('anon', 'anonymous', 0, 'Not logged in.');
INSERT INTO privileges (slug, name, level, description) VALUES ('auth', 'authenticated user', 10, 'No special privilege.');
INSERT INTO privileges (slug, name, level, description) VALUES ('admin', 'group admin', 20, 'Admin of group, building, or organization.');
INSERT INTO privileges (slug, name, level, description) VALUES ('sudo', 'system admin', 30, 'Admin of everything');
