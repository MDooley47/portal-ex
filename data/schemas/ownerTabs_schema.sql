CREATE TABLE "ownerTabs" (id SERIAL PRIMARY KEY, "ownerId" INT, "ownerType" INT NOT NULL, "tabId" INT NOT NULL);


INSERT INTO "ownerTabs" ("ownerId", "ownerType", "tabId") VALUES ('1', '2', "1");
INSERT INTO "ownerTabs" ("ownerId", "ownerType", "tabId") VALUES ('2', '2', "2");
INSERT INTO "ownerTabs" ("ownerId", "ownerType", "tabId") VALUES ('3', '2', "3");
INSERT INTO "ownerTabs" ("ownerId", "ownerType", "tabId") VALUES ('4', '2', "4");
