CREATE TABLE apps (slug TEXT PRIMARY KEY, name TEXT NOT NULL, url TEXT NOT NULL, "iconPath" TEXT NOT NULL, version INT NOT NULL DEFAULT 0);


INSERT INTO apps (slug, name, url, "iconPath") VALUES ('orG332', 'Google Drive', 'https://accounts.google.com/ServiceLogin?service=wise&ltmpl=drive', '/images/google_drive.png');
INSERT INTO apps (slug, name, url, "iconPath") VALUES ('29ry38', 'Google Docs', 'https://docs.google.com', '/images/google_docs.png');
INSERT INTO apps (slug, name, url, "iconPath") VALUES ('ri12io', 'Digital Ocean', 'https://digitalocean.com', '/images/digital_ocean.png');
INSERT INTO apps (slug, name, url, "iconPath") VALUES ('JH3ed1', 'Gmail', 'https://gmail.com', '/images/gmail.png');
INSERT INTO apps (slug, name, url, "iconPath") VALUES ('sNe34a', 'UNOmaha', 'https://unomaha.edu', '/images/unomaha.jpg');
INSERT INTO apps (slug, name, url, "iconPath") VALUES ('via3s3', 'Ubuntu', 'https://ubuntu.com', '/images/ubuntu.png');
