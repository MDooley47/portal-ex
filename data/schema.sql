CREATE TABLE app (id SERIAL PRIMARY KEY, name varchar(255) NOT NULL, url varchar(255) NOT NULL, "iconPath" varchar(255) NOT NULL);


INSERT INTO app (name, url, "iconPath") VALUES ('Google Drive', 'https://accounts.google.com/ServiceLogin?service=wise&ltmpl=drive', '/volumes/storage/images/google_drive.png');
INSERT INTO app (name, url, "iconPath") VALUES ('Google Docs', 'https://docs.google.com', '/volumes/storage/images/google_docs.png');
INSERT INTO app (name, url, "iconPath") VALUES ('Digital Ocean', 'https://digitalocean.com', '/volumes/storage/images/digital_ocean.png');
INSERT INTO app (name, url, "iconPath") VALUES ('Gmail', 'https://gmail.com', '/volumes/storage/images/gmail.png');
INSERT INTO app (name, url, "iconPath") VALUES ('UNOmaha', 'https://unomaha.edu', '/volumes/storage/images/unomaha.jpg');
INSERT INTO app (name, url, "iconPath") VALUES ('Ubuntu', 'https://ubuntu.com', '/volumes/storage/images/ubuntu.png');
