CREATE TABLE apps (id SERIAL PRIMARY KEY, slug text NOT NULL UNIQUE, name text NOT NULL, url text NOT NULL, "iconPath" text NOT NULL, version INT NOT NULL DEFAULT 0);


INSERT INTO apps (name, slug, url, "iconPath") VALUES ('Google Drive', 'orG332', 'https://accounts.google.com/ServiceLogin?service=wise&ltmpl=drive', '/volumes/storage/images/google_drive.png');
INSERT INTO apps (name, slug, url, "iconPath") VALUES ('Google Docs', '29ry38', 'https://docs.google.com', '/volumes/storage/images/google_docs.png');
INSERT INTO apps (name, slug, url, "iconPath") VALUES ('Digital Ocean', 'ri12io', 'https://digitalocean.com', '/volumes/storage/images/digital_ocean.png');
INSERT INTO apps (name, slug, url, "iconPath") VALUES ('Gmail', 'JH3ed1', 'https://gmail.com', '/volumes/storage/images/gmail.png');
INSERT INTO apps (name, slug, url, "iconPath") VALUES ('UNOmaha', 'sNe34a', 'https://unomaha.edu', '/volumes/storage/images/unomaha.jpg');
INSERT INTO apps (name, slug, url, "iconPath") VALUES ('Ubuntu', 'via3s3', 'https://ubuntu.com', '/volumes/storage/images/ubuntu.png');
