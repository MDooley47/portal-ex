CREATE TABLE "userPrivileges" (id SERIAL PRIMARY KEY, "userSlug" TEXT NOT NULL, "privilegeSlug" TEXT NOT NULL DEFAULT 'anon', "groupSlug" TEXT);


INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('njwdq3', 'auth');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('un9f2e', 'auth');
INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug", "groupSlug") VALUES ('un9f2e', 'admin', '29ry38');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('3ehu9Q', 'auth');
INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug", "groupSlug") VALUES ('3ehu9Q', 'auth', 'orG332');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('uhf83W', 'auth');
INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug", "groupSlug") VALUES ('uhf83W', 'auth', 'orG332');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('9ddsoj', 'auth');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('huwei2', 'auth');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('DSAF23', 'auth');

INSERT INTO "userPrivileges" ("userSlug", "privilegeSlug") VALUES ('8u2fSA', 'auth');
