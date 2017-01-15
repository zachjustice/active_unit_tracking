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
-- tb_department
----------------------------------

CREATE TABLE tb_unit(
    unit SERIAL PRIMARY KEY,
    name VARCHAR UNIQUE NOT NULL,
);

----------------------------------
-- tb_department
----------------------------------

CREATE TABLE tb_department(
    department SERIAL PRIMARY KEY,
    name VARCHAR UNIQUE NOT NULL
);

----------------------------------
-- tb_station_type
----------------------------------

CREATE TABLE tb_station_type(
    station_type SERIAL PRIMARY KEY,
    name VARCHAR UNIQUE NOT NULL,
    department INTEGER NOT NULL REFERENCES tb_department(department)
);

----------------------------------
-- tb_station
----------------------------------

CREATE TABLE tb_station(
    station SERIAL PRIMARY KEY,
    name VARCHAR UNIQUE NOT NULL,
    station_type INTEGER NOT NULL REFERENCES tb_station_type(station_type)
);

----------------------------------
-- tb_entity
----------------------------------

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

CREATE TABLE tb_time_entry(
    time_entry BIGSERIAL PRIMARY KEY,
    barcode_scanner_id INTEGER NOT NULL,
    unit VARCHAR(20),
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

