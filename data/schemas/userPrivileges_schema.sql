CREATE TABLE "userPrivileges" (id SERIAL PRIMARY KEY, "userId" INT NOT NULL, "privilegeId" INT NOT NULL DEFAULT 1, "groupId" INT);


INSERT INTO "userPrivileges" ("userId", "privilegeId") VALUES ('1', '4');

INSERT INTO "userPrivileges" ("userId", "privilegeId") VALUES ('2', '2');
INSERT INTO "userPrivileges" ("userId", "privilegeId", "groupId") VALUES ('2', '3', '2');

INSERT INTO "userPrivileges" ("userId", "privilegeId") VALUES ('3', '2');
INSERT INTO "userPrivileges" ("userId", "privilegeId", "groupId") VALUES ('3', '2', '1');

INSERT INTO "userPrivileges" ("userId", "privilegeId") VALUES ('4', '2');
INSERT INTO "userPrivileges" ("userId", "privilegeId", "groupId") VALUES ('4', '2', '1');

INSERT INTO "userPrivileges" ("userId", "privilegeId") VALUES ('5', '2');

INSERT INTO "userPrivileges" ("userId", "privilegeId") VALUES ('6', '2');

INSERT INTO "userPrivileges" ("userId", "privilegeId") VALUES ('7', '2');

INSERT INTO "userPrivileges" ("userId", "privilegeId") VALUES ('8', '2');
