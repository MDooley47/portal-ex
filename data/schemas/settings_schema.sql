CREATE TABLE settings (id SERIAL PRIMARY KEY, slug text NOT NULL UNIQUE, data JSONB NOT NULL);


INSERT INTO settings (slug, data) VALUES ('orG332', '{ "name": "settings example", "type": "layout", "target": "TAB_ID", "owner": { "id": "OWNER_ID", "type": "OWNER_TYPE" }, "data": { "0": "APP_SLUG", "1": "APP_SLUG" }}');
