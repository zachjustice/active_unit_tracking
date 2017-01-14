CREATE OR RETURNS TRIGGER AS $$
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

CREATE TABLE tb_time_entry(
    time_entry SERIAL PRIMARY KEY,
    barcode_scanner_id INTEGER NOT NULL,
    unit VARCHAR(20),
    employee INTEGER,
    station VARCHAR(128),
    start_time TIMESTAMP,
    end_time TIMESTAMP,
    created TIMESTAMP,
    modified TIMESTAMP,
    CONSTRAINT valid_unit_station_or_employee
        CHECK( unit IS NOT NULL OR 
            station IS NOT NULL OR 
            employee_name IS NOT NULL )
);

-- There should be one time entry per unit/station/employee
-- employees should not be working on 2 things at once
-- barcode scanners can be used for multiple concurrent time entries
CREATE UNIQUE INDEX barcode_scanner_id_null_end_date_idx ON tb_time_entry(barcode_scanner_id) WHERE end_time IS NULL;

CREATE UNIQUE INDEX unit_null_end_date_idx ON tb_time_entry(unit) WHERE end_time IS NULL;

CREATE UNIQUE INDEX station_null_end_date_idx ON tb_time_entry(station) WHERE end_time IS NULL;

CREATE UNIQUE INDEX employee_null_end_date_idx ON tb_time_entry(employee) WHERE end_time IS NULL;

CREATE TRIGGER update_time_entry_modified BEFORE UPDATE ON tb_time_entry FOR EACH ROW EXECUTE PROCEDURE update_modified_column();

CREATE TRIGGER update_time_entry_created BEFORE INSERT ON tb_time_entry FOR EACH ROW EXECUTE PROCEDURE update_created_column();

