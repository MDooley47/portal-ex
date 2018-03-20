CREATE TABLE "userPrivileges" (id SERIAL PRIMARY KEY, "userSlug" TEXT NOT NULL, "privilegeSlug" TEXT NOT NULL DEFAULT 'anon', "groupSlug" TEXT);


INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('njwdq3', 'HBFE3d');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('un9f2e', '08hwse');
INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug", "groupSlug") VALUES ('un9f2e', '0eIB3s', '29ry38');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('3ehu9Q', '08hwse');
INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug", "groupSlug") VALUES ('3ehu9Q', '08hwse', 'orG332');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('uhf83W', '08hwse');
INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug", "groupSlug") VALUES ('uhf83W', '08hwse', 'orG332');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('9ddsoj', '08hwse');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('huwei2', '08hwse');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('DSAF23', '08hwse');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('8u2fSA', '08hwse');
