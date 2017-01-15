BEGIN;

CREATE OR REPLACE FUNCTION update_created_column()
RETURNS TRIGGER AS $$
BEGIN
        NEW.created = now();
            RETURN NEW;    
END;
$$ language 'plpgsql';

CREATE OR REPLACE FUNCTION update_modified_column()    
RETURNS TRIGGER AS $$
BEGIN
        NEW.modified = now();
            RETURN NEW;    
END;
$$ language 'plpgsql';

----------------------------------
-- tb_model
----------------------------------

DROP TABLE IF EXISTS tb_model CASCADE;
CREATE TABLE tb_model(
    model SERIAL PRIMARY KEY,
    name VARCHAR UNIQUE NOT NULL,
    created TIMESTAMP,
    modified TIMESTAMP
);

CREATE TRIGGER update_model_modified BEFORE
    UPDATE ON tb_model FOR EACH ROW EXECUTE
    PROCEDURE update_modified_column();

CREATE TRIGGER update_model_created BEFORE
    INSERT ON tb_model FOR EACH ROW EXECUTE 
    PROCEDURE update_created_column();


----------------------------------
-- tb_unit
----------------------------------

DROP TABLE IF EXISTS tb_unit CASCADE;
CREATE TABLE tb_unit(
    unit SERIAL PRIMARY KEY,
    label VARCHAR UNIQUE NOT NULL,
    model INTEGER NOT NULL REFERENCES tb_model(model),
    created TIMESTAMP,
    modified TIMESTAMP
);

CREATE TRIGGER update_unit_modified BEFORE
    UPDATE ON tb_unit FOR EACH ROW EXECUTE
    PROCEDURE update_modified_column();

CREATE TRIGGER update_unit_created BEFORE
    INSERT ON tb_unit FOR EACH ROW EXECUTE 
    PROCEDURE update_created_column();

----------------------------------
-- tb_department
----------------------------------

DROP TABLE IF EXISTS tb_department CASCADE;
CREATE TABLE tb_department(
    department SERIAL PRIMARY KEY,
    name VARCHAR UNIQUE NOT NULL,
    created TIMESTAMP,
    modified TIMESTAMP
);

CREATE TRIGGER update_department_modified BEFORE
    UPDATE ON tb_department FOR EACH ROW EXECUTE
    PROCEDURE update_modified_column();

CREATE TRIGGER update_department_created BEFORE
    INSERT ON tb_department FOR EACH ROW EXECUTE 
    PROCEDURE update_created_column();

----------------------------------
-- tb_station_type
----------------------------------

DROP TABLE IF EXISTS tb_station_type CASCADE;
CREATE TABLE tb_station_type(
    station_type SERIAL PRIMARY KEY,
    name VARCHAR UNIQUE NOT NULL,
    department INTEGER NOT NULL REFERENCES tb_department(department),
    created TIMESTAMP,
    modified TIMESTAMP
);

CREATE TRIGGER update_station_type_modified BEFORE
    UPDATE ON tb_station_type FOR EACH ROW EXECUTE
    PROCEDURE update_modified_column();

CREATE TRIGGER update_station_type_created BEFORE
    INSERT ON tb_station_type FOR EACH ROW EXECUTE 
    PROCEDURE update_created_column();

----------------------------------
-- tb_station
----------------------------------

DROP TABLE IF EXISTS tb_station CASCADE;
CREATE TABLE tb_station(
    station SERIAL PRIMARY KEY,
    name VARCHAR UNIQUE NOT NULL,
    station_type INTEGER NOT NULL REFERENCES tb_station_type(station_type),
    created TIMESTAMP,
    modified TIMESTAMP
);

CREATE TRIGGER update_station_modified BEFORE UPDATE ON tb_station FOR EACH ROW EXECUTE
    PROCEDURE update_modified_column();

CREATE TRIGGER update_station_created BEFORE INSERT ON tb_station FOR EACH ROW EXECUTE 
    PROCEDURE update_created_column();

----------------------------------
-- tb_entity
----------------------------------

DROP TABLE IF EXISTS tb_entity CASCADE;
CREATE TABLE tb_entity(
    entity BIGSERIAL PRIMARY KEY,
    password VARCHAR NOT NULL,
    email_address VARCHAR UNIQUE NOT NULL,
    name VARCHAR NOT NULL,
    created TIMESTAMP,
    modified TIMESTAMP
);

CREATE TRIGGER update_entity_modified BEFORE UPDATE ON tb_entity FOR EACH ROW EXECUTE
    PROCEDURE update_modified_column();

CREATE TRIGGER update_entity_created BEFORE INSERT ON tb_entity FOR EACH ROW EXECUTE 
    PROCEDURE update_created_column();

----------------------------------
-- tb_time_entry
----------------------------------

DROP TABLE IF EXISTS tb_time_entry CASCADE;
CREATE TABLE tb_time_entry(
    time_entry BIGSERIAL PRIMARY KEY,
    barcode_scanner_id INTEGER NOT NULL,
    unit INTEGER REFERENCES tb_unit(unit),
    entity INTEGER REFERENCES tb_entity(entity),
    station INTEGER REFERENCES tb_station(station),
    start_time TIMESTAMP,
    end_time TIMESTAMP,
    created TIMESTAMP,
    modified TIMESTAMP,
    CONSTRAINT valid_unit_station_or_entity
        CHECK( unit IS NOT NULL OR 
            station IS NOT NULL OR 
            entity IS NOT NULL )
);

-- There should be one time entry per unit/station/entity
-- entities should not be working on 2 things at once
-- barcode scanners can be used for multiple concurrent time entries
CREATE UNIQUE INDEX barcode_scanner_id_null_end_date_idx ON tb_time_entry(barcode_scanner_id) WHERE end_time IS NULL;

CREATE UNIQUE INDEX unit_null_end_date_idx ON tb_time_entry(unit) WHERE end_time IS NULL;

CREATE UNIQUE INDEX station_null_end_date_idx ON tb_time_entry(station) WHERE end_time IS NULL;

CREATE UNIQUE INDEX entity_null_end_date_idx ON tb_time_entry(entity) WHERE end_time IS NULL;

CREATE TRIGGER update_time_entry_modified BEFORE UPDATE ON tb_time_entry FOR EACH ROW EXECUTE PROCEDURE update_modified_column();

CREATE TRIGGER update_time_entry_created BEFORE INSERT ON tb_time_entry FOR EACH ROW EXECUTE PROCEDURE update_created_column();

COMMIT;

