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
    employee_name VARCHAR(128),
    station VARCHAR(128),
    start_time TIMESTAMP,
    end_time TIMESTAMP,
    created TIMESTAMP,
    modified TIMESTAMP
    UNIQUE( barcode_scanner_id, end_time )
);

CREATE TRIGGER update_time_entry_modified BEFORE UPDATE ON tb_time_entry FOR EACH ROW EXECUTE PROCEDURE update_modified_column();

CREATE TRIGGER update_time_entry_created BEFORE INSERT ON tb_time_entry FOR EACH ROW EXECUTE PROCEDURE update_created_column();

